<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Reporte;
use App\Models\Evento;
use App\Models\Certificado;
use App\Models\Memorandum;
use App\Models\AuditLog as AuditLogModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;

class ReporteController extends Controller
{
    use AuthorizesRequests;
    /**
     * Mostrar listado de reportes generados.
     */
    public function index()
    {
        $this->authorize('viewAny', Reporte::class);

        $reportes = Reporte::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Obtener datos dinámicos para los criterios
        $eventos = Evento::orderBy('evento')->get(['id', 'evento']);
        $memorandums = Memorandum::select('departamento_mension')->distinct()->get();

        return view('reportes.index', [
            'reportes' => $reportes,
            'tiposDisponibles' => Reporte::tiposDisponibles(),
            'eventos' => $eventos,
            'departamentos' => $memorandums->pluck('departamento_mension')->filter()->unique(),
        ]);
    }

    /**
     * Mostrar formulario para crear reporte.
     */
    public function create()
    {
        $this->authorize('create', Reporte::class);

        return view('reportes.create', [
            'tiposDisponibles' => Reporte::tiposDisponibles(),
            'formatosDisponibles' => Reporte::formatosDisponibles(),
        ]);
    }

    /**
     * Almacenar reporte generado en base de datos y generar archivo.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Reporte::class);

        $validated = $request->validate([
            'tipo_reporte' => ['required', 'string', 'in:certificados_emitidos,uso_sistema,memorandums_departamento,certificados_por_evento,actividad_usuarios,auditoria_sistema'],
            'criterios' => ['nullable', 'array'],
            'fecha_desde' => ['nullable', 'date'],
            'fecha_hasta' => ['nullable', 'date'],
            'formato' => ['required', 'string', 'in:pdf,excel,csv'],
        ]);

        try {
            // Crear registro de reporte
            $reporte = Reporte::create([
                'user_id' => auth()->id(),
                'tipo_reporte' => $validated['tipo_reporte'],
                'criterios' => $validated['criterios'] ?? null,
                'fecha_desde' => $validated['fecha_desde'] ?? null,
                'fecha_hasta' => $validated['fecha_hasta'] ?? null,
                'formato' => $validated['formato'],
                'estado' => 'pendiente',
            ]);

            // Generar datos del reporte según tipo
            $datos = $this->generarDatosReporte(
                $validated['tipo_reporte'],
                $validated['criterios'] ?? [],
                $validated['fecha_desde'],
                $validated['fecha_hasta']
            );

            // Generar archivo
            $archivo = $this->generarArchivo(
                $reporte,
                $datos,
                $validated['formato']
            );

            // Actualizar reporte con información del archivo
            $reporte->update([
                'ruta_archivo' => $archivo['ruta'],
                'cantidad_registros' => $datos['cantidad'],
                'tamaño_archivo' => $archivo['tamaño'],
                'estado' => 'generado',
            ]);

            // Registrar en auditoría
            AuditLog::registrar(
                'generar_reporte',
                Reporte::class,
                $reporte->id,
                null,
                [
                    'tipo' => $validated['tipo_reporte'],
                    'formato' => $validated['formato'],
                    'criterios' => $validated['criterios'] ?? null,
                ]
            );

            return to_route('reportes.index')->with('success', 'Reporte generado exitosamente.');
        } catch (\Exception $e) {
            // Registrar error en auditoría
            AuditLog::registrar(
                'error_generar_reporte',
                Reporte::class,
                null,
                null,
                [
                    'tipo' => $validated['tipo_reporte'],
                    'error' => $e->getMessage(),
                ]
            );

            return back()->with('error', 'Error al generar reporte: ' . $e->getMessage());
        }
    }

    /**
     * Descargar archivo de reporte.
     */
    public function download(Reporte $reporte)
    {
        $this->authorize('download', $reporte);

        if (!$reporte->ruta_archivo || !Storage::disk('public')->exists($reporte->ruta_archivo)) {
            if ($reporte->formato === 'pdf') {
                $this->regenerarReportePdf($reporte);
            } else {
                return back()->with('error', 'Archivo no encontrado.');
            }
        }

        if ($reporte->formato === 'pdf' && !$this->esPdfValido($reporte->ruta_archivo)) {
            $this->regenerarReportePdf($reporte);
        }

        // Registrar descarga en auditoría
        AuditLog::registrar(
            'descargar_reporte',
            Reporte::class,
            $reporte->id,
            null,
            ['formato' => $reporte->formato]
        );

        return Storage::disk('public')->download($reporte->ruta_archivo);
    }

    /**
     * Eliminar reporte.
     */
    public function destroy(Reporte $reporte)
    {
        $this->authorize('delete', $reporte);

        // Eliminar archivo si existe
        if ($reporte->ruta_archivo && Storage::disk('public')->exists($reporte->ruta_archivo)) {
            Storage::disk('public')->delete($reporte->ruta_archivo);
        }

        // Registrar eliminación en auditoría
        AuditLog::registrar(
            'eliminar_reporte',
            Reporte::class,
            $reporte->id,
            $reporte->toArray(),
            null
        );

        $reporte->delete();

        return to_route('reportes.index')->with('success', 'Reporte eliminado.');
    }

    // ──────────────────────────────────────────────
    //  MÉTODOS PRIVADOS PARA GENERAR DATOS
    // ──────────────────────────────────────────────

    /**
     * Generar datos según tipo de reporte.
     */
    private function generarDatosReporte(string $tipo, array $criterios, ?string $fechaDesde, ?string $fechaHasta): array
    {
        return match ($tipo) {
            'certificados_emitidos' => $this->reporteCertificadosEmitidos($criterios, $fechaDesde, $fechaHasta),
            'uso_sistema' => $this->reporteUsoSistema($criterios, $fechaDesde, $fechaHasta),
            'memorandums_departamento' => $this->reporteMemorandumsDepartamento($criterios, $fechaDesde, $fechaHasta),
            'certificados_por_evento' => $this->reporteCertificadosPorEvento($criterios, $fechaDesde, $fechaHasta),
            'actividad_usuarios' => $this->reporteActividadUsuarios($criterios, $fechaDesde, $fechaHasta),
            'auditoria_sistema' => $this->reporteAuditoría($criterios, $fechaDesde, $fechaHasta),
            default => ['datos' => [], 'cantidad' => 0],
        };
    }

    /**
     * Reporte: Certificados Emitidos.
     */
    private function reporteCertificadosEmitidos(array $criterios, ?string $fechaDesde, ?string $fechaHasta): array
    {
        $query = Certificado::with('evento')
            ->select('id', 'evento_id', 'codigo', 'nombre', 'paterno', 'materno', 'created_at');

        if ($fechaDesde) {
            $query->whereDate('created_at', '>=', $fechaDesde);
        }
        if ($fechaHasta) {
            $query->whereDate('created_at', '<=', $fechaHasta);
        }

        if (!empty($criterios['filtro'])) {
            $query->where('evento_id', $criterios['filtro']);
        }

        $certificados = $query->orderBy('created_at', 'desc')->get();

        return [
            'datos' => $certificados->map(fn ($cert) => [
                'id' => $cert->id,
                'codigo' => $cert->codigo,
                'persona' => trim(($cert->nombre ?? '') . ' ' . ($cert->paterno ?? '') . ' ' . ($cert->materno ?? '')),
                'evento' => $cert->evento->evento ?? 'N/A',
                'fecha' => $cert->created_at->format('d/m/Y'),
            ]),
            'cantidad' => $certificados->count(),
            'columnas' => ['ID', 'Código', 'Persona', 'Evento', 'Fecha Emisión'],
            'hoja' => 'Certificados',
        ];
    }

    /**
     * Reporte: Uso del Sistema por Usuario.
     */
    private function reporteUsoSistema(array $criterios, ?string $fechaDesde, ?string $fechaHasta): array
    {
        $query = AuditLogModel::select('user_id', DB::raw('COUNT(*) as cantidad_acciones'))
            ->where('accion', '!=', 'login')
            ->groupBy('user_id');

        if ($fechaDesde) {
            $query->whereDate('created_at', '>=', $fechaDesde);
        }
        if ($fechaHasta) {
            $query->whereDate('created_at', '<=', $fechaHasta);
        }

        $acciones = $query->with('user')
            ->orderBy('cantidad_acciones', 'desc')
            ->get();

        return [
            'datos' => $acciones->map(fn ($log) => [
                'usuario' => $log->user->name ?? 'N/A',
                'email' => $log->user->email ?? 'N/A',
                'acciones' => $log->cantidad_acciones,
            ]),
            'cantidad' => $acciones->count(),
            'columnas' => ['Usuario', 'Email', 'Cantidad de Acciones'],
            'hoja' => 'Uso Sistema',
        ];
    }

    /**
     * Reporte: Memorándums por Departamento.
     */
    private function reporteMemorandumsDepartamento(array $criterios, ?string $fechaDesde, ?string $fechaHasta): array
    {
        $query = Memorandum::select('id', 'evento', 'detalle', 'departamento_mension', 'created_at');

        if ($fechaDesde) {
            $query->whereDate('created_at', '>=', $fechaDesde);
        }
        if ($fechaHasta) {
            $query->whereDate('created_at', '<=', $fechaHasta);
        }

        if (!empty($criterios['filtro'])) {
            $query->where('departamento_mension', $criterios['filtro']);
        }

        $memorandums = $query->orderBy('created_at', 'desc')->get();

        return [
            'datos' => $memorandums->map(fn ($memo) => [
                'evento' => $memo->evento,
                'detalle' => Str::limit($memo->detalle, 40),
                'departamento' => $memo->departamento_mension ?? 'N/A',
                'fecha' => $memo->created_at->format('d/m/Y'),
            ]),
            'cantidad' => $memorandums->count(),
            'columnas' => ['Evento', 'Detalle', 'Departamento', 'Fecha'],
            'hoja' => 'Memorandums',
        ];
    }

    /**
     * Reporte: Certificados por Evento.
     */
    private function reporteCertificadosPorEvento(array $criterios, ?string $fechaDesde, ?string $fechaHasta): array
    {
        $query = DB::table('certificados')
            ->selectRaw('eventos.evento, COUNT(certificados.id) as cantidad')
            ->join('eventos', 'certificados.evento_id', '=', 'eventos.id')
            ->groupBy('eventos.id', 'eventos.evento');

        if ($fechaDesde) {
            $query->whereDate('certificados.created_at', '>=', $fechaDesde);
        }
        if ($fechaHasta) {
            $query->whereDate('certificados.created_at', '<=', $fechaHasta);
        }

        $eventos = $query->orderBy('cantidad', 'desc')->get();

        return [
            'datos' => $eventos->map(fn ($evt) => [
                'evento' => $evt->evento,
                'cantidad' => $evt->cantidad,
            ]),
            'cantidad' => $eventos->count(),
            'columnas' => ['Evento', 'Cantidad de Certificados'],
            'hoja' => 'Por Evento',
        ];
    }

    /**
     * Reporte: Actividad de Usuarios.
     */
    private function reporteActividadUsuarios(array $criterios, ?string $fechaDesde, ?string $fechaHasta): array
    {
        $query = AuditLogModel::select('accion', DB::raw('COUNT(*) as cantidad'))
            ->groupBy('accion');

        if ($fechaDesde) {
            $query->whereDate('created_at', '>=', $fechaDesde);
        }
        if ($fechaHasta) {
            $query->whereDate('created_at', '<=', $fechaHasta);
        }

        $acciones = $query->orderBy('cantidad', 'desc')->get();

        return [
            'datos' => $acciones->map(fn ($acc) => [
                'accion' => str_replace('_', ' ', ucfirst($acc->accion)),
                'cantidad' => $acc->cantidad,
            ]),
            'cantidad' => $acciones->count(),
            'columnas' => ['Acción', 'Cantidad'],
            'hoja' => 'Actividad',
        ];
    }

    /**
     * Reporte: Auditoría del Sistema.
     */
    private function reporteAuditoría(array $criterios, ?string $fechaDesde, ?string $fechaHasta): array
    {
        $query = AuditLogModel::with('user')
            ->select('id', 'user_id', 'accion', 'entidad', 'created_at');

        if ($fechaDesde) {
            $query->whereDate('created_at', '>=', $fechaDesde);
        }
        if ($fechaHasta) {
            $query->whereDate('created_at', '<=', $fechaHasta);
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        return [
            'datos' => $logs->map(fn ($log) => [
                'usuario' => $log->user->name ?? 'N/A',
                'accion' => str_replace('_', ' ', ucfirst($log->accion)),
                'entidad' => $log->entidad,
                'fecha' => $log->created_at->format('d/m/Y H:i'),
            ]),
            'cantidad' => $logs->count(),
            'columnas' => ['Usuario', 'Acción', 'Entidad', 'Fecha'],
            'hoja' => 'Auditoria',
        ];
    }

    // ──────────────────────────────────────────────
    //  MÉTODOS PRIVADOS PARA GENERAR ARCHIVOS
    // ──────────────────────────────────────────────

    /**
     * Generar archivo en formato especificado.
     */
    private function generarArchivo(Reporte $reporte, array $datos, string $formato): array
    {
        $timestamp = now()->format('YmdHis');
        $nombreBase = 'reporte_' . $reporte->tipo_reporte . '_' . $timestamp;
        $ruta = 'reportes/';

        if (!Storage::disk('public')->exists($ruta)) {
            Storage::disk('public')->makeDirectory($ruta);
        }

        return match ($formato) {
            'pdf' => $this->generarPDF($datos, $nombreBase, $ruta),
            'excel' => $this->generarExcel($datos, $nombreBase, $ruta),
            'csv' => $this->generarCSV($datos, $nombreBase, $ruta),
            default => throw new \Exception('Formato no soportado'),
        };
    }

    /**
     * Generar archivo PDF.
     */
    private function generarPDF(array $datos, string $nombreBase, string $ruta): array
    {
        $html = $this->construirHtmlReportePdf($datos, 'Reporte del Sistema', auth()->user()?->name ?? 'Sistema');
        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'landscape');

        $nombreArchivo = $nombreBase . '.pdf';
        $rutaCompleta = $ruta . $nombreArchivo;

        Storage::disk('public')->put($rutaCompleta, $pdf->output());

        return [
            'ruta' => $rutaCompleta,
            'tamaño' => strlen($pdf->output()),
        ];
    }

    /**
     * Generar archivo Excel.
     */
    private function generarExcel(array $datos, string $nombreBase, string $ruta): array
    {
        $hoja = str_replace(['\\', '/', '?', '*', '[', ']', ':'], '', (string) ($datos['hoja'] ?? 'Reporte'));
        $hoja = mb_substr(trim($hoja), 0, 31) ?: 'Reporte';
        $encabezados = '';
        foreach ($datos['columnas'] as $columna) {
            $encabezados .= '<th style="background:#1e3a8a;color:#fff;padding:10px;border:1px solid #cbd5e1;font-size:12px;">' . e($columna) . '</th>';
        }

        $filas = '';
        foreach ($datos['datos'] as $fila) {
            $filas .= '<tr>';
            foreach ((array) $fila as $valor) {
                $filas .= '<td style="padding:8px;border:1px solid #dbe4f0;font-size:11px;">' . e((string) $valor) . '</td>';
            }
            $filas .= '</tr>';
        }

        $contenido = '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color:#0f172a; }
        .title { font-size:18px; font-weight:700; color:#1e3a8a; margin-bottom:6px; }
        .meta { color:#64748b; font-size:11px; margin-bottom:12px; }
        table { width:100%; border-collapse:collapse; }
        .summary { background:#f8fafc; border:1px solid #e2e8f0; padding:8px 10px; border-radius:8px; margin-bottom:12px; font-size:11px; }
    </style>
</head>
<body>
    <div class="title">' . e($hoja) . '</div>
    <div class="meta">Generado el ' . now()->format('d/m/Y H:i') . ' | Sistema de Certificaciones y Memorándums Digitales</div>
    <div class="summary"><strong>Total de registros:</strong> ' . e((string) ($datos['cantidad'] ?? 0)) . '</div>
    <table>
        <thead><tr>' . $encabezados . '</tr></thead>
        <tbody>' . ($filas !== '' ? $filas : '<tr><td colspan="' . count($datos['columnas']) . '" style="padding:10px;border:1px solid #e2e8f0;">Sin datos disponibles</td></tr>') . '</tbody>
    </table>
    <div style="margin-top:20px; text-align:right; color:#475569; font-size:10px;">
        Generado por: <strong>' . e(auth()->user()?->name ?? 'Sistema') . '</strong>
    </div>
</body>
</html>';

        $nombreArchivo = $nombreBase . '.xls';
        $rutaCompleta = $ruta . $nombreArchivo;

        Storage::disk('public')->put($rutaCompleta, $contenido);

        return [
            'ruta' => $rutaCompleta,
            'tamaño' => strlen($contenido),
        ];
    }

    /**
     * Generar archivo CSV.
     */
    private function generarCSV(array $datos, string $nombreBase, string $ruta): array
    {
        $output = fopen('php://memory', 'w');

        // BOM UTF-8 para evitar caracteres raros en Excel (tildes/ñ)
        fwrite($output, "\xEF\xBB\xBF");

        // Escribir encabezados
        fputcsv($output, $datos['columnas'], ';');

        // Escribir datos
        foreach ($datos['datos'] as $fila) {
            fputcsv($output, array_values((array) $fila), ';');
        }

        rewind($output);
        $contenido = stream_get_contents($output);
        fclose($output);

        $nombreArchivo = $nombreBase . '.csv';
        $rutaCompleta = $ruta . $nombreArchivo;

        Storage::disk('public')->put($rutaCompleta, $contenido);

        return [
            'ruta' => $rutaCompleta,
            'tamaño' => strlen($contenido),
        ];
    }

    /**
     * Regenera un reporte PDF guardado con formato anterior o archivo corrupto.
     */
    private function regenerarReportePdf(Reporte $reporte): void
    {
        $datos = $this->generarDatosReporte(
            $reporte->tipo_reporte,
            $reporte->criterios ?? [],
            $reporte->fecha_desde?->format('Y-m-d'),
            $reporte->fecha_hasta?->format('Y-m-d')
        );

        $nombreBase = pathinfo($reporte->ruta_archivo ?: ('reporte_' . $reporte->id . '.pdf'), PATHINFO_FILENAME);
        $ruta = 'reportes/';
        $archivo = $this->generarPDF($datos, $nombreBase, $ruta);

        $reporte->update([
            'ruta_archivo' => $archivo['ruta'],
            'cantidad_registros' => $datos['cantidad'],
            'tamaño_archivo' => $archivo['tamaño'],
            'estado' => 'generado',
            'mensaje_error' => null,
        ]);
    }

    /**
     * Verifica que el archivo guardado sea un PDF real.
     */
    private function esPdfValido(string $rutaArchivo): bool
    {
        $contenido = Storage::disk('public')->get($rutaArchivo);

        return str_starts_with($contenido, '%PDF');
    }

    /**
     * Construye el HTML base para un PDF de reporte.
     */
    private function construirHtmlReportePdf(array $datos, string $titulo, string $generadoPor): string
    {
        $encabezados = '';
        foreach ($datos['columnas'] as $columna) {
            $encabezados .= '<th style="background:#1e3a8a;color:#fff;padding:10px;border:1px solid #cbd5e1;font-size:12px;text-align:left;">' . e($columna) . '</th>';
        }

        $filas = '';
        foreach ($datos['datos'] as $fila) {
            $filas .= '<tr>';
            foreach ((array) $fila as $valor) {
                $filas .= '<td style="padding:9px;border:1px solid #e2e8f0;font-size:11px;">' . e((string) $valor) . '</td>';
            }
            $filas .= '</tr>';
        }

        return '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 20px 20px 85px 20px; }
        body { font-family: DejaVu Sans, sans-serif; color: #0f172a; font-size: 12px; }
        .header { border-bottom: 2px solid #1e3a8a; padding-bottom: 12px; margin-bottom: 18px; }
        .title { font-size: 20px; font-weight: bold; color: #1e3a8a; margin: 0 0 4px 0; }
        .meta { color: #64748b; font-size: 11px; margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 14px; margin-bottom: 62px; }
        .summary { background: #f8fafc; border: 1px solid #e2e8f0; padding: 10px 12px; border-radius: 8px; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <p class="title">' . e($titulo) . '</p>
        <p class="meta">Generado el ' . now()->format('d/m/Y H:i') . ' | Sistema de Certificaciones y Memorándums Digitales</p>
    </div>
    <div class="summary">
        <strong>Total de registros:</strong> ' . e((string) ($datos['cantidad'] ?? 0)) . '
    </div>
    <table>
        <thead><tr>' . $encabezados . '</tr></thead>
        <tbody>' . ($filas !== '' ? $filas : '<tr><td colspan="' . count($datos['columnas']) . '" style="padding:10px;border:1px solid #e2e8f0;">Sin datos disponibles</td></tr>') . '</tbody>
    </table>
    <div style="position:fixed; bottom:10px; right:22px; text-align:right; color:#475569; font-size:11px;">
        <div style="border-top:1px solid #cbd5e1; display:inline-block; padding-top:8px; min-width:240px;">
            Generado por: <strong>' . e($generadoPor) . '</strong>
        </div>
    </div>
</body>
</html>';
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Certificado;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;

class CertificadoController extends Controller
{
    public function index() {}

    public function create(string $evento_id)
    {
        return view('certificados.create', [
            'evento_id' => $evento_id
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'evento_id' => ['required'],
            'tipo' => ['required', 'string'],
            'carnet' => ['nullable'],
            'grado' => ['nullable'],
            'nombre' => ['required', 'string'],
            'paterno' => ['nullable'],
            'materno' => ['nullable'],
            'correo' => ['nullable', 'email'],
            'telefono' => ['nullable', 'string'],
        ]);

        $certificado = Certificado::create([
            'evento_id' => $request->input('evento_id'),
            'tipo' => $request->input('tipo'),
            'carnet' => $request->input('carnet'),
            'grado' => $request->input('grado'),
            'nombre' => $request->input('nombre'),
            'paterno' => $request->input('paterno'),
            'materno' => $request->input('materno'),
            'correo' => $request->input('correo'),
            'telefono' => $request->input('telefono'),
        ]);
        
        // Registrar en auditoría
        AuditLog::registrar(
            'emitir_certificado',
            Certificado::class,
            $certificado->id,
            null,
            $certificado->toArray()
        );
        
        return to_route('eventos.show', $request->input('evento_id'));
    }

    public function edit(string $id)
    {
        $certificado = Certificado::findOrFail($id);
        return view('certificados.edit', [
            'certificado' => $certificado
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'codigo' => ['required'],
            'tipo' => ['required', 'string'],
            'carnet' => ['nullable'],
            'grado' => ['nullable'],
            'nombre' => ['required', 'string'],
            'paterno' => ['nullable'],
            'materno' => ['nullable'],
            'correo' => ['nullable', 'email'],
            'telefono' => ['nullable', 'string'],
        ]);

        $certificado = Certificado::findOrFail($id);
        $datosAnteriores = $certificado->toArray();
        
        $certificado->update([
            'codigo' => $request->input('codigo'),
            'tipo' => $request->input('tipo'),
            'carnet' => $request->input('carnet'),
            'grado' => $request->input('grado'),
            'nombre' => $request->input('nombre'),
            'paterno' => $request->input('paterno'),
            'materno' => $request->input('materno'),
            'correo' => $request->input('correo'),
            'telefono' => $request->input('telefono'),
        ]);
        
        // Registrar en auditoría
        AuditLog::registrar(
            'actualizar_certificado',
            Certificado::class,
            $certificado->id,
            $datosAnteriores,
            $certificado->fresh()->toArray()
        );
        
        return to_route('eventos.show', $certificado->evento_id);
    }

    public function destroy(string $id)
    {
        $certificado = Certificado::findOrFail($id);
        $datosAnteriores = $certificado->toArray();
        $eventoId = $certificado->evento_id;
        
        $certificado->delete();
        
        // Registrar en auditoría
        AuditLog::registrar(
            'eliminar_certificado',
            Certificado::class,
            $id,
            $datosAnteriores,
            null
        );
        
        return to_route('eventos.show', $eventoId);
    }

    public function certificado(string $id)
    {
        ini_set('max_execution_time', 300);
        
        $certificado = Certificado::with('evento.fondo')->findOrFail($id);
        $evento = $certificado->evento;

        // Verificar que el evento tenga diseño configurado
        if (!$evento->fondo_id || !$evento->tenor_html) {
            return redirect()->route('eventos.show', $evento->id)
                ->with('error', 'El evento no tiene un diseño de certificado configurado. Por favor, diseña el certificado primero.');
        }

        // 1. Preparación de fechas
        Carbon::setLocale('es');
        $fecha_inicio = $evento->fecha_inicio ? Carbon::parse($evento->fecha_inicio)->translatedFormat('l, j \de F \de Y') : 'Sin definir';
        $fecha_fin = $evento->fecha_fin ? Carbon::parse($evento->fecha_fin)->translatedFormat('l, j \de F \de Y') : 'Sin definir';

        // 2. QR Code (SVG base64, no requiere imagick)
        $encabezadoTexto = strip_tags($evento->encabezado_html ?? '');
        $encabezadoTexto = trim(preg_replace('/\s+/', ' ', $encabezadoTexto));
        $url = "Codigo: $certificado->id\n"
            . "Nombre: $certificado->grado $certificado->nombre $certificado->paterno $certificado->materno\n"
            . "Evento: $evento->evento\n"
            . "Tipo: $certificado->tipo\n"
            . "Fecha inicio: $fecha_inicio\n"
            . "Fecha fin: $fecha_fin\n"
            . "Encabezado: $encabezadoTexto";
        $qrSvg = QrCode::format('svg')->encoding('UTF-8')->size(155)->generate($url);
        $qr = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

        // 3. Fondo e imágenes Base64
        $fondoBase64 = $this->fileToBase64(public_path($evento->fondo->ruta_fondo));

        // 4. Fuentes como Base64 para @font-face en las vistas
        $fontRobotoRegular    = $this->fileToBase64(public_path('fonts/Roboto-Regular.ttf'));
        $fontRobotoSlab       = $this->fileToBase64(public_path('fonts/Roboto-Slab.ttf'));
        $fontRobotoSlabBold   = $this->fileToBase64(public_path('fonts/RobotoSlab-Bold.ttf'));
        $fontGreatVibes       = $this->fileToBase64(public_path('fonts/GreatVibes-Regular.ttf'));

        // 5. Reemplazar variables en encabezado y tenor
        $encabezadoFinal = $this->normalizarFuentes(
            $this->limpiarColorEncabezado(
                $this->reemplazarVariables($evento->encabezado_html ?? '', $certificado, $evento, $fecha_inicio, $fecha_fin)
            )
        );
        $tenorFinal = $this->normalizarFuentes(
            $this->reemplazarVariables($evento->tenor_html, $certificado, $evento, $fecha_inicio, $fecha_fin)
        );

        // 6. Renderizar vista con el fondo correspondiente
        $html = view("pdf.fondos." . $evento->fondo->codigo, [
            'evento' => $evento,
            'certificado' => $certificado,
            'qr' => $qr,
            'fondoBase64' => $fondoBase64,
            'fontRobotoRegular' => $fontRobotoRegular,
            'fontRobotoSlab' => $fontRobotoSlab,
            'fontRobotoSlabBold' => $fontRobotoSlabBold,
            'fontGreatVibes' => $fontGreatVibes,
            'encabezadoFinal' => $encabezadoFinal,
            'tenorFinal' => $tenorFinal,
                'generadoPor' => auth()->user()?->name ?? 'Sistema',
        ])->render();

        // 7. Configuración de Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', false);
        $options->set('isHtml5ParserEnabled', true);
        // Apuntar al directorio donde ya están las fuentes pre-instaladas
        $options->set('fontDir', public_path('fonts'));
        $options->set('fontCache', public_path('fonts'));
        $options->set('defaultFont', 'roboto slab');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        
        // Configurar orientación según el fondo
        $orientacion = $evento->fondo->orientacion == 'portrait' ? 'portrait' : 'landscape';
        $dompdf->setPaper('letter', $orientacion);
        $dompdf->render();

        $pdfOutput = $dompdf->output();

        // 8. Guardar en storage
        $directory = storage_path("app/{$evento->id}");
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        file_put_contents("$directory/$certificado->id.pdf", $pdfOutput);

        AuditLog::registrar(
            'generar_certificado',
            Certificado::class,
            $certificado->id,
            null,
            [
                'evento_id' => $evento->id,
                'certificado_id' => $certificado->id,
                'tipo' => $certificado->tipo,
                'nombre' => trim("{$certificado->grado} {$certificado->nombre} {$certificado->paterno} {$certificado->materno}"),
            ]
        );

        return response($pdfOutput, 200)
            ->header('Content-Type', 'application/pdf');
    }

    // Método auxiliar para reemplazar variables
    private function reemplazarVariables($html, $certificado, $evento, $fecha_inicio, $fecha_fin)
    {
        $nombreCompleto = trim("$certificado->grado $certificado->nombre $certificado->paterno $certificado->materno");
        
        $variables = [
            '{{grado}}' => $certificado->grado ?? '',
            '{{nombre}}' => $certificado->nombre ?? '',
            '{{paterno}}' => $certificado->paterno ?? '',
            '{{materno}}' => $certificado->materno ?? '',
            '{{nombre_completo}}' => $nombreCompleto,
            '{{tipo}}' => $certificado->tipo ?? '',
            '{{carnet}}' => $certificado->carnet ?? '',
            '{{correo}}' => $certificado->correo ?? '',
            '{{telefono}}' => $certificado->telefono ?? '',
            '{{evento}}' => $evento->evento ?? '',
            '{{detalle}}' => $evento->detalle ?? '',
            '{{fecha_inicio}}' => $fecha_inicio,
            '{{fecha_fin}}' => $fecha_fin,
        ];

        return str_replace(array_keys($variables), array_values($variables), $html);
    }

    // Limpia colores inline del encabezado para que el CSS del blade tome control
    private function limpiarColorEncabezado($html)
    {
        // Eliminar color inline de style="... color: ...; ..."
        $html = preg_replace('/\s*color\s*:\s*[^;\"]+;?/i', '', $html);
        return $html;
    }

    // Normaliza fuentes inline para que DomPDF las reconozca correctamente
    private function normalizarFuentes($html)
    {
        // Quill guarda fuentes con &quot; escapado, DomPDF necesita comillas normales
        // &quot;Roboto Slab&quot; → 'Roboto Slab'
        $html = str_replace('&quot;Roboto Slab&quot;', "'Roboto Slab'", $html);
        $html = str_replace('&quot;Great Vibes&quot;', "'Great Vibes'", $html);
        $html = str_replace('&quot;Roboto&quot;', "'Roboto'", $html);
        return $html;
    }

    // Función genérica para imágenes y fuentes
    private function fileToBase64($path)
    {
        if (!file_exists($path)) return '';
        
        $mime = mime_content_type($path);
        
        // Ajuste manual para fuentes ttf si mime_content_type falla o retorna octet-stream
        if (str_ends_with($path, '.ttf')) {
            $mime = 'font/truetype';
        }

        return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
    }

    public function generarMasivo(Request $request)
    {
        // Incrementar el tiempo de ejecución
        set_time_limit(3600); // 1 hora
        ini_set('max_execution_time', 3600);
        
        $request->validate([
            'evento_id' => 'required|exists:eventos,id',
            'id_desde' => 'required|integer|min:1',
            'id_hasta' => 'required|integer|min:1',
        ]);

        $id_desde = $request->input('id_desde');
        $id_hasta = $request->input('id_hasta');
        $evento_id = $request->input('evento_id');

        // Validar que el rango sea correcto
        if ($id_desde > $id_hasta) {
            return redirect()->route('eventos.show', $evento_id)
                ->with('error', 'El ID desde debe ser menor o igual al ID hasta');
        }

        // Obtener evento con fondo
        $evento = \App\Models\Evento::with('fondo')->findOrFail($evento_id);

        // Verificar que el evento tenga diseño configurado
        if (!$evento->fondo_id || !$evento->tenor_html) {
            return redirect()->route('eventos.show', $evento_id)
                ->with('error', 'El evento no tiene un diseño de certificado configurado. Por favor, diseña el certificado primero.');
        }

        // Obtener los certificados en el rango POR ID
        $certificados = Certificado::where('evento_id', $evento_id)
            ->whereBetween('id', [$id_desde, $id_hasta])
            ->get();

        if ($certificados->isEmpty()) {
            return redirect()->route('eventos.show', $evento_id)
                ->with('error', "No se encontraron certificados con IDs entre $id_desde y $id_hasta para este evento");
        }

        // Crear directorio de almacenamiento para el evento
        $directory = storage_path("app/{$evento_id}");
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $generados = 0;
        $errores = 0;
        $detalleErrores = [];

        // Pre-cargar recursos comunes (para optimizar)
        $fondoBase64 = $this->fileToBase64(public_path($evento->fondo->ruta_fondo));
        $fontRobotoRegular = $this->fileToBase64(public_path('fonts/Roboto-Regular.ttf'));
        $fontRobotoSlab = $this->fileToBase64(public_path('fonts/Roboto-Slab.ttf'));
        $fontGreatVibes = $this->fileToBase64(public_path('fonts/GreatVibes-Regular.ttf'));

        // Generar cada certificado
        foreach ($certificados as $certificado) {
            try {
                // Preparación de datos
                Carbon::setLocale('es');
                $fecha_inicio = $evento->fecha_inicio ? Carbon::parse($evento->fecha_inicio)->translatedFormat('l, j \de F \de Y') : 'Sin definir';
                $fecha_fin = $evento->fecha_fin ? Carbon::parse($evento->fecha_fin)->translatedFormat('l, j \de F \de Y') : 'Sin definir';

                $encabezadoTexto = strip_tags($evento->encabezado_html ?? '');
                $encabezadoTexto = trim(preg_replace('/\s+/', ' ', $encabezadoTexto));
                $url = "Codigo: $certificado->id\n"
                    . "Nombre: $certificado->grado $certificado->nombre $certificado->paterno $certificado->materno\n"
                    . "Evento: $evento->evento\n"
                    . "Tipo: $certificado->tipo\n"
                    . "Fecha inicio: $fecha_inicio\n"
                    . "Fecha fin: $fecha_fin\n"
                    . "Encabezado: $encabezadoTexto";
                $qrSvg = QrCode::format('svg')->encoding('UTF-8')->size(155)->generate($url);
                $qr = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

                // Reemplazar variables
                $encabezadoFinal = $this->limpiarColorEncabezado(
                    $this->reemplazarVariables($evento->encabezado_html ?? '', $certificado, $evento, $fecha_inicio, $fecha_fin)
                );
                $tenorFinal = $this->reemplazarVariables($evento->tenor_html, $certificado, $evento, $fecha_inicio, $fecha_fin);

                $html = view("pdf.fondos." . $evento->fondo->codigo, [
                    'evento' => $evento,
                    'certificado' => $certificado,
                    'qr' => $qr,
                    'fondoBase64' => $fondoBase64,
                    'fontRobotoRegular' => $fontRobotoRegular,
                    'fontRobotoSlab' => $fontRobotoSlab,
                    'fontGreatVibes' => $fontGreatVibes,
                    'encabezadoFinal' => $encabezadoFinal,
                    'tenorFinal' => $tenorFinal,
                        'generadoPor' => auth()->user()?->name ?? 'Sistema',
                ])->render();

                // Configuración de Dompdf
                $options = new Options();
                $options->set('isRemoteEnabled', false);
                $options->set('isHtml5ParserEnabled', true);
                
                $fontCache = storage_path('framework/cache/fonts');
                if (!is_dir($fontCache)) {
                    mkdir($fontCache, 0775, true);
                }
                $options->set('fontCache', $fontCache);
                $options->set('defaultFont', 'Roboto');

                $dompdf = new Dompdf($options);
                $dompdf->loadHtml($html);
                
                $orientacion = $evento->fondo->orientacion == 'portrait' ? 'portrait' : 'landscape';
                $dompdf->setPaper('letter', $orientacion);
                $dompdf->render();

                $pdfOutput = $dompdf->output();

                // Guardar en storage
                file_put_contents("$directory/{$certificado->id}.pdf", $pdfOutput);
                
                $generados++;

            } catch (\DivisionByZeroError $e) {
                $errores++;
                $detalleErrores[] = "ID {$certificado->id}: Imagen con dimensiones inválidas o corrupta";
                continue;
            } catch (\Exception $e) {
                $errores++;
                $detalleErrores[] = "ID {$certificado->id}: " . $e->getMessage();
                continue;
            } catch (\Throwable $e) {
                $errores++;
                $detalleErrores[] = "ID {$certificado->id}: Error fatal - " . $e->getMessage();
                continue;
            }
        }

        // Redirigir con mensaje de éxito
        $mensaje = "Se generaron correctamente {$generados} certificado(s) en storage/app/{$evento_id}/";
        if ($errores > 0) {
            $mensaje .= " | Errores: {$errores}";
        }
        
        return redirect()->route('eventos.show', $evento_id)
            ->with('success', $mensaje)
            ->with('detalleErrores', $detalleErrores);
    }
}
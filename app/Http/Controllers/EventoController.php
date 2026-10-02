<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Evento;
use App\Models\FondoCertificado;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EventoController extends Controller
{
    public function index()
    {
        $eventos = DB::select('select id,evento,detalle,fecha_inicio,fecha_fin
        from eventos
        order by id desc');
        return view('eventos.index',[
            'eventos'=>$eventos
        ]);
    }

    public function create()
    {
        return view('eventos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'evento'=>['required','string'],
            'detalle'=>['nullable','string'],
            'fecha_inicio'=>['required','date'],
            'fecha_fin'=>['required','date'],
        ]);

        $evento = Evento::create([
            'evento'=>$request->input('evento'),
            'detalle'=>$request->input('detalle'),
            'fecha_inicio'=>$request->input('fecha_inicio'),
            'fecha_fin'=>$request->input('fecha_fin'),
        ]);
        
        // Registrar en auditoría
        AuditLog::registrar(
            'crear_evento',
            Evento::class,
            $evento->id,
            null,
            $evento->toArray()
        );
        
        return to_route('eventos.index');
    }

    public function show(string $id)
    {
        $evento = Evento::findOrFail($id);
        $certificados = $evento->certificados;
        return view('certificados.index',[
            'evento'=>$evento,
            'certificados'=>$certificados
        ]);
    }

    public function edit(string $id)
    {
        $evento = Evento::findOrFail($id);
        return view('eventos.edit',[
            'evento'=>$evento
        ]);
    }

    public function update(Request $request, string $id)
    {
        $evento = Evento::findOrFail($id);
        $datosAnteriores = $evento->toArray();
        
        $request->validate([
            'evento'=>['required','string'],
            'detalle'=>['nullable','string'],
            'fecha_inicio'=>['required','date'],
            'fecha_fin'=>['required','date'],
        ]);
        $evento->update([
            'evento'=>$request->input('evento'),
            'detalle'=>$request->input('detalle'),
            'fecha_inicio'=>$request->input('fecha_inicio'),
            'fecha_fin'=>$request->input('fecha_fin'),
        ]);
        
        // Registrar en auditoría
        AuditLog::registrar(
            'actualizar_evento',
            Evento::class,
            $evento->id,
            $datosAnteriores,
            $evento->fresh()->toArray()
        );

        return to_route('eventos.index');
    }

    public function destroy(string $id)
    {
        $evento = Evento::findOrFail($id); // Encuentra el evento o lanza un error 404
        $datosAnteriores = $evento->toArray();
        
        $evento->delete(); // Elimina el evento
        
        // Registrar en auditoría
        AuditLog::registrar(
            'eliminar_evento',
            Evento::class,
            $id,
            $datosAnteriores,
            null
        );
    
        return redirect()->route('eventos.index');
    }

    // Métodos para diseño de certificados
    public function seleccionarFondo(string $id)
    {
        $evento = Evento::findOrFail($id);
        $fondos = FondoCertificado::where('activo', true)->orderBy('orden')->get();
        
        return view('eventos.seleccionar-fondo', [
            'evento' => $evento,
            'fondos' => $fondos
        ]);
    }

    public function guardarFondo(Request $request, string $id)
    {
        $evento = Evento::findOrFail($id);
        $request->validate([
            'fondo_id' => 'required|exists:fondos_certificados,id'
        ]);

        $evento->update([
            'fondo_id' => $request->input('fondo_id')
        ]);

        return redirect()->route('eventos.disenar', $id);
    }

    public function disenarCertificado(string $id)
    {
        $evento = Evento::with('fondo')->findOrFail($id);
        
        if (!$evento->fondo_id) {
            return redirect()->route('eventos.seleccionarFondo', $id)
                ->with('info', 'Primero debes seleccionar un fondo para el certificado');
        }

        return view('eventos.disenar-certificado', [
            'evento' => $evento
        ]);
    }

    public function guardarDiseno(Request $request, string $id)
    {
        $evento = Evento::findOrFail($id);
        
        $request->validate([
            'encabezado_html' => 'nullable|string',
            'tenor_html' => 'required|string',
        ]);

        $evento->update([
            'encabezado_html' => $request->input('encabezado_html'),
            'tenor_html' => $request->input('tenor_html'),
        ]);

        return redirect()->route('eventos.show', $id)
            ->with('success', 'Diseño de certificado guardado correctamente');
    }

    public function previsualizarDiseno(Request $request, string $id)
    {
        $evento = Evento::with('fondo')->findOrFail($id);

        if (!$evento->fondo_id) {
            return redirect()->route('eventos.seleccionarFondo', $id)
                ->with('info', 'Primero debes seleccionar un fondo para el certificado');
        }

        Carbon::setLocale('es');
        $fecha_inicio = $evento->fecha_inicio ? Carbon::parse($evento->fecha_inicio)->translatedFormat('l, j \de F \de Y') : 'Sin definir';
        $fecha_fin = $evento->fecha_fin ? Carbon::parse($evento->fecha_fin)->translatedFormat('l, j \de F \de Y') : 'Sin definir';

        $certificado = (object) [
            'id' => 0,
            'grado' => 'Lic.',
            'nombre' => 'Nombre de ejemplo',
            'paterno' => 'Apellido paterno',
            'materno' => 'Apellido materno',
            'tipo' => 'Participante',
            'carnet' => '',
            'correo' => '',
            'telefono' => '',
        ];

        $encabezadoTexto = strip_tags($evento->encabezado_html ?? '');
        $encabezadoTexto = trim(preg_replace('/\s+/', ' ', $encabezadoTexto));
        $url = "Codigo: {$certificado->id}\n"
            . "Nombre: {$certificado->grado} {$certificado->nombre} {$certificado->paterno} {$certificado->materno}\n"
            . "Evento: {$evento->evento}\n"
            . "Tipo: {$certificado->tipo}\n"
            . "Fecha inicio: {$fecha_inicio}\n"
            . "Fecha fin: {$fecha_fin}\n"
            . "Encabezado: {$encabezadoTexto}";
        $qrSvg = QrCode::format('svg')->encoding('UTF-8')->size(155)->generate($url);
        $qr = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

        $encabezadoHtml = $request->input('encabezado_html', $evento->encabezado_html ?? '');
        $tenorHtml = $request->input('tenor_html', $evento->tenor_html ?? '');

        $encabezadoFinal = $this->normalizarFuentes(
            $this->limpiarColorEncabezado(
                $this->reemplazarVariables($encabezadoHtml, $certificado, $evento, $fecha_inicio, $fecha_fin)
            )
        );
        $tenorFinal = $this->normalizarFuentes(
            $this->reemplazarVariables($tenorHtml, $certificado, $evento, $fecha_inicio, $fecha_fin)
        );

        $html = view('pdf.fondos.' . $evento->fondo->codigo, [
            'evento' => $evento,
            'certificado' => $certificado,
            'qr' => $qr,
            'fondoBase64' => $this->fileToBase64(public_path($evento->fondo->ruta_fondo)),
            'fontRobotoRegular' => $this->fileToBase64(public_path('fonts/Roboto-Regular.ttf')),
            'fontRobotoSlab' => $this->fileToBase64(public_path('fonts/Roboto-Slab.ttf')),
            'fontRobotoSlabBold' => $this->fileToBase64(public_path('fonts/RobotoSlab-Bold.ttf')),
            'fontGreatVibes' => $this->fileToBase64(public_path('fonts/GreatVibes-Regular.ttf')),
            'encabezadoFinal' => $encabezadoFinal,
            'tenorFinal' => $tenorFinal,
            'modoPreview' => true,
                'generadoPor' => auth()->user()?->name ?? 'Sistema',
            ])->render();

        return response($html, 200)
            ->header('Content-Type', 'text/html; charset=UTF-8');
    }

    private function reemplazarVariables($html, $certificado, $evento, $fecha_inicio, $fecha_fin)
    {
        $nombreCompleto = trim("{$certificado->grado} {$certificado->nombre} {$certificado->paterno} {$certificado->materno}");

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

    private function limpiarColorEncabezado($html)
    {
        return preg_replace('/\s*color\s*:\s*[^;\"]+;?/i', '', $html);
    }

    private function normalizarFuentes($html)
    {
        $html = str_replace('&quot;Roboto Slab&quot;', "'Roboto Slab'", $html);
        $html = str_replace('&quot;Great Vibes&quot;', "'Great Vibes'", $html);
        $html = str_replace('&quot;Roboto&quot;', "'Roboto'", $html);
        return $html;
    }

    private function fileToBase64($path)
    {
        if (!file_exists($path)) {
            return '';
        }

        $mime = mime_content_type($path) ?: 'application/octet-stream';

        if (str_ends_with($path, '.ttf')) {
            $mime = 'font/truetype';
        }

        return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\FondoCertificado;
use App\Models\MCertificado;
use App\Models\Memorandum;
use App\Models\Persona;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Container\Attributes\DB;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MemorandumController extends Controller
{
    public function index()
    {
        $memorandums = Memorandum::all();
        return view('memorandums.index',[
            'memorandums'=>$memorandums
        ]);
    }

    public function create()
    {
        return view('memorandums.create');
    }

    public function store(Request $request)
    {
         $request->validate([
            'evento'=>['required','string'],
            'detalle'=>['nullable','string'],
            'resolucion'=>['nullable','string'],
            'fecha_resolucion'=>['required','date'],
            'carrera'=>['nullable','string'],
            'departamento_mension'=>['nullable','string'],
            'catedra'=>['nullable','string'],
        ]);

        $memorandum = Memorandum::create([
            'evento'=>$request->input('evento'),
            'detalle'=>$request->input('detalle'),
            'resolucion'=>$request->input('resolucion'),
            'fecha_resolucion'=>$request->input('fecha_resolucion'),
            'carrera'=>$request->input('carrera'),
            'departamento_mension'=>$request->input('departamento_mension'),
            'catedra'=>$request->input('catedra'),
        ]);
        
        // Registrar en auditoría
        AuditLog::registrar(
            'crear_memorandum',
            Memorandum::class,
            $memorandum->id,
            null,
            $memorandum->toArray()
        );
        
        return to_route('memorandums.index');
    }

    public function show(string $id)
    {
        $memorandum    = Memorandum::findOrFail($id);
        $personas      = $memorandum->personas;
        $mcertificados = \App\Models\MCertificado::whereIn('persona_id', $personas->pluck('id'))
            ->with('persona')
            ->orderBy('id')
            ->get();
        return view('personas.index',[
            'memorandum'    => $memorandum,
            'personas'      => $personas,
            'mcertificados' => $mcertificados,
        ]);
    }

    public function edit(string $id)
    {
                $memorandum = Memorandum::findOrFail($id);
        return view('memorandums.edit',[
            'memorandum'=>$memorandum
        ]);
    }

    public function update(Request $request, string $id)
    {
        $memorandum = Memorandum::findOrFail($id);
        $datosAnteriores = $memorandum->toArray();
        
        $request->validate([
            'evento'=>['required','string'],
            'detalle'=>['nullable','string'],
            'resolucion'=>['nullable','string'],
            'fecha_resolucion'=>['required','date'],
            'carrera'=>['nullable','string'],
            'departamento_mension'=>['nullable','string'],
            'catedra'=>['nullable','string'],
        ]);
        $memorandum->update([
            'evento'=>$request->input('evento'),
            'detalle'=>$request->input('detalle'),
            'resolucion'=>$request->input('resolucion'),
            'fecha_resolucion'=>$request->input('fecha_resolucion'),
            'carrera'=>$request->input('carrera'),
            'departamento_mension'=>$request->input('departamento_mension'),
            'catedra'=>$request->input('catedra'),
        ]);
        
        // Registrar en auditoría
        AuditLog::registrar(
            'actualizar_memorandum',
            Memorandum::class,
            $memorandum->id,
            $datosAnteriores,
            $memorandum->fresh()->toArray()
        );

        return to_route('memorandums.index');
    }

    public function destroy(string $id)
    {
        $memorandum = Memorandum::findOrFail($id);
        $datosAnteriores = $memorandum->toArray();
        
        $memorandum->delete();
        
        // Registrar en auditoría
        AuditLog::registrar(
            'eliminar_memorandum',
            Memorandum::class,
            $id,
            $datosAnteriores,
            null
        );
        
        return redirect()->route('memorandums.index');
    }

    public function disenar(string $id)
    {
        $memorandum = Memorandum::findOrFail($id);
        return view('memorandums.disenar-memorandum', [
            'memorandum' => $memorandum,
        ]);
    }

    public function guardarDiseno(Request $request, string $id)
    {
        $memorandum = Memorandum::findOrFail($id);

        $request->validate([
            'encabezado_html'  => 'nullable|string',
            'cab_html'         => 'nullable|string',
            'destinatario_html'=> 'nullable|string',
            'tenor_html'       => 'nullable|string',
        ]);

        $memorandum->update([
            'encabezado_html'  => $request->input('encabezado_html'),
            'cab_html'         => $request->input('cab_html'),
            'destinatario_html'=> $request->input('destinatario_html'),
            'tenor_html'       => $request->input('tenor_html'),
        ]);

        return redirect()->route('memorandums.show', $id)
            ->with('success', 'Diseño del memorandum guardado correctamente');
    }

    public function previsualizarDiseno(Request $request, string $id)
    {
        $memorandum = Memorandum::findOrFail($id);

        Carbon::setLocale('es');
        $fecha_resolucion = $memorandum->fecha_resolucion 
            ? Carbon::createFromFormat('Y-m-d', $memorandum->fecha_resolucion)->translatedFormat('j \de F \de Y')
            : 'Sin definir';
        $fecha_actual = Carbon::now()->locale('es')->translatedFormat('j \de F \de Y');

        $persona = (object) [
            'id' => 0,
            'grado' => 'Lic.',
            'nombre' => 'Nombre de ejemplo',
            'paterno' => 'Apellido paterno',
            'materno' => 'Apellido materno',
            'tipo' => 'Participante',
        ];

        $vars = [
            '{{grado}}'            => $persona->grado ?? '',
            '{{nombre}}'           => $persona->nombre ?? '',
            '{{paterno}}'          => $persona->paterno ?? '',
            '{{materno}}'          => $persona->materno ?? '',
            '{{tipo}}'             => $persona->tipo ?? '',
            '{{resolucion}}'       => $memorandum->resolucion ?? '',
            '{{fecha_resolucion}}' => $fecha_resolucion,
            '{{fecha_actual}}'     => $fecha_actual,
            '{{detalle}}'          => $memorandum->detalle ?? '',
            '{{evento}}'           => $memorandum->evento ?? '',
        ];

        $defaultEncabezado = '<p><strong>UNIVERSIDAD MAYOR DE SAN ANDRÉS</strong></p><p>FACULTAD DE MEDICINA, ENFERMERÍA, NUTRICIÓN Y TECNOLOGÍA MÉDICA</p><p>VICEDECANATO</p>';
        $defaultCab = '<p><strong>UNIVERSIDAD MAYOR DE SAN ANDRÉS</strong></p><p>FACULTAD DE MEDICINA, ENFERMERÍA,</p><p>NUTRICIÓN Y TECNOLOGÍA MÉDICA</p><p><strong>VICEDECANATO</strong></p>';
        $defaultDestinatario = '<p>Señor(a):</p><p>{{grado}} {{nombre}} {{paterno}} {{materno}}</p><p><strong>{{tipo}}</strong> - Facultad de Medicina, Enfermería, Nutrición y Tecnología Médica</p><p>Presente.-</p><p><br></p><p>{{fecha_actual}}</p>';
        $defaultTenor = '<p>De mi consideración:</p><p>En cumplimiento a la <strong>{{resolucion}}</strong> de fecha {{fecha_resolucion}}, de la Facultad de Medicina, Enfermería, Nutrición y Tecnología Médica, comunico a usted que ha sido designado para conformar el <strong>{{detalle}}</strong> para la <strong>{{evento}}</strong>.</p><p>Deseandole éxitos en las funciones asignadas, saludo a usted muy cordialmente.</p><p>Atentamente,</p>';

        $encabezadoFinal = strtr($request->input('encabezado_html', $memorandum->encabezado_html ?? $defaultEncabezado), $vars);
        $cabFinal = strtr($request->input('cab_html', $memorandum->cab_html ?? $defaultCab), $vars);
        $destinatarioFinal = strtr($request->input('destinatario_html', $memorandum->destinatario_html ?? $defaultDestinatario), $vars);
        $tenorFinal = strtr($request->input('tenor_html', $memorandum->tenor_html ?? $defaultTenor), $vars);

        $qrData = "Codigo: {$persona->id}\n"
            . "Nombre: {$persona->grado} {$persona->nombre} {$persona->paterno} {$persona->materno}\n"
            . "Evento: {$memorandum->evento}\n"
            . "Detalle: {$memorandum->detalle}\n"
            . "Tipo: {$persona->tipo}\n"
            . "Resolucion: {$memorandum->resolucion}\n"
            . "Fecha resolución: {$fecha_resolucion}";
        $qrSvg = QrCode::format('svg')->encoding('UTF-8')->size(175)->generate($qrData);
        $qr = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

        $fondoPath = public_path('fondo-memo/fondomemo.jpg');
        $fondoBase64 = file_exists($fondoPath)
            ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($fondoPath))
            : '';

        $html = view('memos.1', [
            'memorandum'         => $memorandum,
            'persona'            => $persona,
            'qr'                 => $qr,
            'encabezadoFinal'    => $encabezadoFinal,
            'cabFinal'           => $cabFinal,
            'destinatarioFinal'  => $destinatarioFinal,
            'tenorFinal'         => $tenorFinal,
            'fontRobotoRegular'  => 'file://' . public_path('fonts/Roboto-Regular.ttf'),
            'fontRobotoSlab'     => 'file://' . public_path('fonts/Roboto-Slab.ttf'),
            'fontRobotoSlabBold' => 'file://' . public_path('fonts/RobotoSlab-Bold.ttf'),
            'fontGreatVibes'     => 'file://' . public_path('fonts/GreatVibes-Regular.ttf'),
            'fondoBase64'        => $fondoBase64,
            'modoPreview'        => true,
        ])->render();

        return response($html, 200)
            ->header('Content-Type', 'text/html; charset=UTF-8');
    }

    public function generarMasivo(Request $request)
    {
        set_time_limit(3600);
        ini_set('max_execution_time', 3600);
        ini_set('memory_limit', '512M');

        $request->validate([
            'memorandum_id' => 'required|exists:memorandums,id',
            'id_desde'      => 'required|integer|min:1',
            'id_hasta'      => 'required|integer|min:1',
        ]);

        $memorandum_id = $request->input('memorandum_id');
        $id_desde      = $request->input('id_desde');
        $id_hasta      = $request->input('id_hasta');

        if ($id_desde > $id_hasta) {
            return redirect()->route('memorandums.show', $memorandum_id)
                ->with('error', 'El ID desde debe ser menor o igual al ID hasta');
        }

        $memorandum = Memorandum::findOrFail($memorandum_id);

        $personas = Persona::where('memorandum_id', $memorandum_id)
            ->whereBetween('id', [$id_desde, $id_hasta])
            ->get();

        if ($personas->isEmpty()) {
            return redirect()->route('memorandums.show', $memorandum_id)
                ->with('error', "No se encontraron personas con IDs entre $id_desde y $id_hasta para este memorandum");
        }

        $directory = storage_path("app/memos/{$memorandum_id}");
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Pre-cargar recursos comunes
        $fondoPath   = public_path('fondo-memo/fondomemo.jpg');
        $fondoBase64 = file_exists($fondoPath) ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($fondoPath)) : '';
        $fontRobotoRegular  = 'file://' . public_path('fonts/Roboto-Regular.ttf');
        $fontRobotoSlab     = 'file://' . public_path('fonts/Roboto-Slab.ttf');
        $fontRobotoSlabBold = 'file://' . public_path('fonts/RobotoSlab-Bold.ttf');
        $fontGreatVibes     = 'file://' . public_path('fonts/GreatVibes-Regular.ttf');

        $defaultEncabezado   = '<p><strong>UNIVERSIDAD MAYOR DE SAN ANDRÉS</strong></p><p>FACULTAD DE MEDICINA, ENFERMERÍA, NUTRICIÓN Y TECNOLOGÍA MÉDICA</p><p>VICEDECANATO</p>';
        $defaultCab          = '<p><strong>UNIVERSIDAD MAYOR DE SAN ANDRÉS</strong></p><p>FACULTAD DE MEDICINA, ENFERMERÍA,</p><p>NUTRICIÓN Y TECNOLOGÍA MÉDICA</p><p><strong>VICEDECANATO</strong></p>';
        $defaultDestinatario = '<p>Señor(a):</p><p>{{grado}} {{nombre}} {{paterno}} {{materno}}</p><p><strong>{{tipo}}</strong> - Facultad de Medicina, Enfermería, Nutrición y Tecnología Médica</p><p>Presente.-</p><p><br></p><p>{{fecha_actual}}</p>';
        $defaultTenor        = '<p>De mi consideración:</p><p>En cumplimiento a la <strong>{{resolucion}}</strong> de fecha {{fecha_resolucion}}, de la Facultad de Medicina, Enfermería, Nutrición y Tecnología Médica, comunico a usted que ha sido designado para conformar el <strong>{{detalle}}</strong> para la <strong>{{evento}}</strong>.</p><p>Deseandole éxitos en las funciones asignadas, saludo a usted muy cordialmente.</p><p>Atentamente,</p>';

        $generados = 0;
        $errores = 0;
        $detalleErrores = [];

        foreach ($personas as $persona) {
            try {
                Carbon::setLocale('es');
                $fecha_resolucion = $memorandum->fecha_resolucion 
                    ? Carbon::createFromFormat('Y-m-d', $memorandum->fecha_resolucion)->translatedFormat('j \de F \de Y')
                    : 'Sin definir';
                $fecha_actual = Carbon::now()->locale('es')->translatedFormat('j \de F \de Y');

                $vars = [
                    '{{grado}}'            => $persona->grado ?? '',
                    '{{nombre}}'           => $persona->nombre ?? '',
                    '{{paterno}}'          => $persona->paterno ?? '',
                    '{{materno}}'          => $persona->materno ?? '',
                    '{{tipo}}'             => $persona->tipo ?? '',
                    '{{resolucion}}'       => $memorandum->resolucion ?? '',
                    '{{fecha_resolucion}}' => $fecha_resolucion,
                    '{{fecha_actual}}'     => $fecha_actual,
                    '{{detalle}}'          => $memorandum->detalle ?? '',
                    '{{evento}}'           => $memorandum->evento ?? '',
                ];

                $encabezadoFinal   = strtr($memorandum->encabezado_html   ?? $defaultEncabezado,   $vars);
                $cabFinal          = strtr($memorandum->cab_html           ?? $defaultCab,          $vars);
                $destinatarioFinal = strtr($memorandum->destinatario_html  ?? $defaultDestinatario, $vars);
                $tenorFinal        = strtr($memorandum->tenor_html         ?? $defaultTenor,        $vars);

                $qrData = "Codigo: {$persona->id}\nNombre: {$persona->grado} {$persona->nombre} {$persona->paterno} {$persona->materno}\nEvento: {$memorandum->evento}\nDetalle: {$memorandum->detalle}\nTipo: {$persona->tipo}\nResolucion: {$memorandum->resolucion}\nFecha resolución: {$fecha_resolucion}";
                $qrSvg = QrCode::format('svg')->encoding('UTF-8')->size(175)->generate($qrData);
                $qr = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

                $html = view('memos.1', [
                    'memorandum'        => $memorandum,
                    'persona'           => $persona,
                    'qr'                => $qr,
                    'encabezadoFinal'   => $encabezadoFinal,
                    'cabFinal'          => $cabFinal,
                    'destinatarioFinal' => $destinatarioFinal,
                    'tenorFinal'        => $tenorFinal,
                    'fontRobotoRegular'  => $fontRobotoRegular,
                    'fontRobotoSlab'     => $fontRobotoSlab,
                    'fontRobotoSlabBold' => $fontRobotoSlabBold,
                    'fontGreatVibes'     => $fontGreatVibes,
                    'fondoBase64'       => $fondoBase64,
                    'generadoPor'        => auth()->user()?->name ?? 'Sistema',
                ])->render();

                $options = new Options();
                $options->set('defaultFont', 'Roboto Slab');
                $options->set('isRemoteEnabled', true);
                $options->set('isFontSubsettingEnabled', true);
                $options->set('fontDir', public_path('fonts'));
                $options->set('fontCache', public_path('fonts'));

                $dompdf = new Dompdf($options);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('letter', 'portrait');
                $dompdf->render();

                file_put_contents("$directory/{$persona->id}.pdf", $dompdf->output());
                $generados++;

            } catch (\Exception $e) {
                $errores++;
                $detalleErrores[] = "ID {$persona->id}: " . $e->getMessage();
                \Illuminate\Support\Facades\Log::error("generarMasivo persona {$persona->id}: " . $e->getMessage());
                continue;
            } catch (\Throwable $e) {
                $errores++;
                $detalleErrores[] = "ID {$persona->id}: Error fatal - " . $e->getMessage();
                \Illuminate\Support\Facades\Log::error("generarMasivo persona {$persona->id} FATAL: " . $e->getMessage());
                continue;
            }
        }

        $mensaje = "Se generaron correctamente {$generados} memorandum(s) en storage/app/memos/{$memorandum_id}/";
        if ($errores > 0) {
            $mensaje .= " | Errores: {$errores}";
        }

        return redirect()->route('memorandums.show', $memorandum_id)
            ->with('success', $mensaje)
            ->with('detalleErrores', $detalleErrores);
    }

    // ──────────────────────────────────────────────
    //  CERTIFICADOS DESDE MEMORANDUMS
    // ──────────────────────────────────────────────

    public function seleccionarFondoCert(string $id)
    {
        $memorandum = Memorandum::findOrFail($id);
        $fondos = FondoCertificado::where('activo', true)->orderBy('orden')->get();
        return view('memorandums.seleccionar-fondo-cert', compact('memorandum', 'fondos'));
    }

    public function guardarFondoCert(Request $request, string $id)
    {
        $memorandum = Memorandum::findOrFail($id);
        $request->validate(['fondo_id' => 'required|exists:fondos_certificados,id']);
        $memorandum->update(['fondo_id' => $request->input('fondo_id')]);
        return redirect()->route('memorandums.disenarCert', $id);
    }

    public function disenarCertificado(string $id)
    {
        $memorandum = Memorandum::findOrFail($id);
        return view('memorandums.disenar-certificado-memo', compact('memorandum'));
    }

    public function guardarDisenoCert(Request $request, string $id)
    {
        $memorandum = Memorandum::findOrFail($id);
        $request->validate([
            'cert_encabezado_html' => 'nullable|string',
            'cert_tenor_html'      => 'required|string',
        ]);
        $memorandum->update([
            'cert_encabezado_html' => $request->input('cert_encabezado_html'),
            'cert_tenor_html'      => $request->input('cert_tenor_html'),
        ]);
        return redirect()->route('memorandums.show', $id)
            ->with('success', 'Diseño de certificado guardado correctamente');
    }

    public function previsualizarDisenoCert(Request $request, string $id)
    {
        $memorandum = Memorandum::findOrFail($id);
        $persona = $memorandum->personas()->orderBy('id')->first();

        if (!$persona) {
            $persona = new Persona([
                'id'       => $memorandum->id,
                'grado'    => '',
                'nombre'   => '',
                'paterno'  => '',
                'materno'  => '',
                'tipo'     => '',
                'carnet'   => '',
                'correo'   => '',
                'telefono' => '',
            ]);
        }

        $mcert = $persona->mcertificado ?? (object) ['id' => $memorandum->id];

        Carbon::setLocale('es');
        $fecha_resolucion = $memorandum->fecha_resolucion 
            ? Carbon::createFromFormat('Y-m-d', $memorandum->fecha_resolucion)->translatedFormat('j \de F \de Y')
            : 'Sin definir';

        $vars = [
            '{{grado}}'            => $persona->grado,
            '{{nombre}}'           => $persona->nombre,
            '{{paterno}}'          => $persona->paterno,
            '{{materno}}'          => $persona->materno,
            '{{tipo}}'             => $persona->tipo,
            '{{resolucion}}'       => $memorandum->resolucion ?? '',
            '{{fecha_resolucion}}' => $fecha_resolucion,
            '{{detalle}}'          => $memorandum->detalle ?? '',
            '{{evento}}'           => $memorandum->evento ?? '',
            '{{carrera}}'          => $memorandum->carrera ?? '',
            '{{departamento}}'     => $memorandum->departamento_mension ?? '',
            '{{catedra}}'          => $memorandum->catedra ?? '',
        ];

        $encabezadoHtml = $request->input('cert_encabezado_html', $memorandum->cert_encabezado_html ?? '');
        $tenorHtml = $request->input('cert_tenor_html', $memorandum->cert_tenor_html ?? '');

        $encabezadoFinal = $this->normalizarFuentesCert(strtr($encabezadoHtml, $vars));
        $tenorFinal = $this->normalizarFuentesCert(strtr($tenorHtml, $vars));

        $qrData = "Codigo: {$mcert->id}\n"
            . "Nombre: {$persona->grado} {$persona->nombre} {$persona->paterno} {$persona->materno}\n"
            . "Evento: {$memorandum->evento}\n"
            . "Detalle: {$memorandum->detalle}\n"
            . "Tipo: {$persona->tipo}\n"
            . "Resolucion: {$memorandum->resolucion}\n"
            . "Fecha: {$fecha_resolucion}";
        $qrSvg = QrCode::format('svg')->encoding('UTF-8')->size(155)->generate($qrData);
        $qr = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

        $html = view('pdf.memo-cert', [
            'mcert'              => $mcert,
            'persona'            => $persona,
            'qr'                 => $qr,
            'fondoBase64'        => $this->fileToBase64Cert(public_path('fondo-memo/fondomemocer.jpg')),
            'fontRobotoRegular'  => $this->fileToBase64Cert(public_path('fonts/Roboto-Regular.ttf')),
            'fontRobotoSlab'     => $this->fileToBase64Cert(public_path('fonts/Roboto-Slab.ttf')),
            'fontRobotoSlabBold' => $this->fileToBase64Cert(public_path('fonts/RobotoSlab-Bold.ttf')),
            'fontGreatVibes'     => $this->fileToBase64Cert(public_path('fonts/GreatVibes-Regular.ttf')),
            'encabezadoFinal'    => $encabezadoFinal,
            'tenorFinal'         => $tenorFinal,
            'modoPreview'        => true,
            'generadoPor'        => auth()->user()?->name ?? 'Sistema',
        ])->render();

        return response($html, 200)
            ->header('Content-Type', 'text/html; charset=UTF-8');
    }

    public function certPersona(string $memorandumId, string $personaId)
    {
        ini_set('max_execution_time', 300);

        $memorandum = Memorandum::findOrFail($memorandumId);
        $persona    = Persona::where('id', $personaId)
                        ->where('memorandum_id', $memorandumId)
                        ->firstOrFail();

        if ($persona->estado !== 'aprobado') {
            return redirect()->route('memorandums.show', $memorandumId)
                ->with('error', 'Esta persona no tiene estado aprobado para generar certificado.');
        }

        if (!$memorandum->cert_tenor_html) {
            return redirect()->route('memorandums.show', $memorandumId)
                ->with('error', 'El memorándum no tiene diseño de certificado configurado. Por favor, diseña el certificado primero.');
        }

        Carbon::setLocale('es');
        $fecha_resolucion = $memorandum->fecha_resolucion 
            ? Carbon::createFromFormat('Y-m-d', $memorandum->fecha_resolucion)->translatedFormat('j \de F \de Y')
            : 'Sin definir';

        $vars = [
            '{{grado}}'            => $persona->grado           ?? '',
            '{{nombre}}'           => $persona->nombre          ?? '',
            '{{paterno}}'          => $persona->paterno         ?? '',
            '{{materno}}'          => $persona->materno         ?? '',
            '{{tipo}}'             => $persona->tipo            ?? '',
            '{{resolucion}}'       => $memorandum->resolucion   ?? '',
            '{{fecha_resolucion}}' => $fecha_resolucion,
            '{{detalle}}'          => $memorandum->detalle      ?? '',
            '{{evento}}'           => $memorandum->evento       ?? '',
            '{{carrera}}'          => $memorandum->carrera      ?? '',
            '{{departamento}}'     => $memorandum->departamento_mension ?? '',
            '{{catedra}}'          => $memorandum->catedra      ?? '',
        ];

        $encabezadoFinal = $this->normalizarFuentesCert(strtr($memorandum->cert_encabezado_html ?? '', $vars));
        $tenorFinal      = $this->normalizarFuentesCert(strtr($memorandum->cert_tenor_html, $vars));

        // Obtener o crear MCertificado para esta persona
        $mcert = MCertificado::firstOrCreate(['persona_id' => $persona->id]);

        // QR con el id del MCertificado
        $qrData = "Codigo: {$mcert->id}\nNombre: {$persona->grado} {$persona->nombre} {$persona->paterno} {$persona->materno}\nEvento: {$memorandum->evento}\nDetalle: {$memorandum->detalle}\nTipo: {$persona->tipo}\nResolucion: {$memorandum->resolucion}\nFecha: {$fecha_resolucion}";
        $qrSvg  = QrCode::format('svg')->encoding('UTF-8')->size(155)->generate($qrData);
        $qr     = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

        // Fondo fijo para certificados de memorándum
        $fondoBase64        = $this->fileToBase64Cert(public_path('fondo-memo/fondomemocer.jpg'));
        $fontRobotoRegular  = $this->fileToBase64Cert(public_path('fonts/Roboto-Regular.ttf'));
        $fontRobotoSlab     = $this->fileToBase64Cert(public_path('fonts/Roboto-Slab.ttf'));
        $fontRobotoSlabBold = $this->fileToBase64Cert(public_path('fonts/RobotoSlab-Bold.ttf'));
        $fontGreatVibes     = $this->fileToBase64Cert(public_path('fonts/GreatVibes-Regular.ttf'));

        $html = view('pdf.memo-cert', [
            'mcert'               => $mcert,
            'persona'             => $persona,
            'qr'                  => $qr,
            'fondoBase64'         => $fondoBase64,
            'fontRobotoRegular'   => $fontRobotoRegular,
            'fontRobotoSlab'      => $fontRobotoSlab,
            'fontRobotoSlabBold'  => $fontRobotoSlabBold,
            'fontGreatVibes'      => $fontGreatVibes,
            'encabezadoFinal'     => $encabezadoFinal,
            'tenorFinal'          => $tenorFinal,
                    'generadoPor'         => auth()->user()?->name ?? 'Sistema',
        ])->render();

        $options = new Options();
        $options->set('isRemoteEnabled', false);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('fontDir', public_path('fonts'));
        $options->set('fontCache', public_path('fonts'));
        $options->set('defaultFont', 'roboto slab');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('letter', 'portrait');
        $dompdf->render();

        $pdfOutput = $dompdf->output();
        $directory = storage_path("app/certs-memo/{$memorandumId}");
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        $destino  = "$directory/{$mcert->id}.pdf";
        $temporal = "$directory/{$mcert->id}_" . uniqid() . '.tmp';
        file_put_contents($temporal, $pdfOutput);
        if (file_exists($destino)) { @unlink($destino); }
        rename($temporal, $destino);

        AuditLog::registrar(
            'generar_certificado',
            Persona::class,
            $persona->id,
            null,
            [
                'memorandum_id' => $memorandumId,
                'persona_id'    => $persona->id,
                'mcert_id'      => $mcert->id,
                'nombre'        => trim("{$persona->grado} {$persona->nombre} {$persona->paterno} {$persona->materno}"),
                'tipo'          => $persona->tipo,
            ]
        );

        return response($pdfOutput, 200)->header('Content-Type', 'application/pdf');
    }

    public function generarMasivoCert(Request $request)
    {
        set_time_limit(3600);
        ini_set('max_execution_time', 3600);
        ini_set('memory_limit', '512M');

        $request->validate([
            'memorandum_id' => 'required|exists:memorandums,id',
        ]);

        $memorandum_id = $request->input('memorandum_id');
        $memorandum    = Memorandum::findOrFail($memorandum_id);

        if (!$memorandum->cert_tenor_html) {
            return redirect()->route('memorandums.show', $memorandum_id)
                ->with('error', 'El memorándum no tiene un diseño de certificado configurado.');
        }

        // Solo personas con estado aprobado
        $personas = Persona::where('memorandum_id', $memorandum_id)
            ->where('estado', 'aprobado')
            ->get();

        if ($personas->isEmpty()) {
            return redirect()->route('memorandums.show', $memorandum_id)
                ->with('error', 'No hay personas con estado aprobado en este memorándum.');
        }

        $directory = storage_path("app/certs-memo/{$memorandum_id}");
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Pre-cargar recursos comunes (fondo fijo para certificados de memo)
        $fondoBase64        = $this->fileToBase64Cert(public_path('fondo-memo/fondomemocer.jpg'));
        $fontRobotoRegular  = $this->fileToBase64Cert(public_path('fonts/Roboto-Regular.ttf'));
        $fontRobotoSlab     = $this->fileToBase64Cert(public_path('fonts/Roboto-Slab.ttf'));
        $fontRobotoSlabBold = $this->fileToBase64Cert(public_path('fonts/RobotoSlab-Bold.ttf'));
        $fontGreatVibes     = $this->fileToBase64Cert(public_path('fonts/GreatVibes-Regular.ttf'));

        $generados = 0;
        $errores   = 0;
        $detalleErrores = [];
        $certGenerados  = [];

        foreach ($personas as $persona) {
            try {
                Carbon::setLocale('es');
                $fecha_resolucion = $memorandum->fecha_resolucion 
                    ? Carbon::createFromFormat('Y-m-d', $memorandum->fecha_resolucion)->translatedFormat('j \de F \de Y')
                    : 'Sin definir';

                $vars = [
                    '{{grado}}'            => $persona->grado           ?? '',
                    '{{nombre}}'           => $persona->nombre          ?? '',
                    '{{paterno}}'          => $persona->paterno         ?? '',
                    '{{materno}}'          => $persona->materno         ?? '',
                    '{{tipo}}'             => $persona->tipo            ?? '',
                    '{{resolucion}}'       => $memorandum->resolucion   ?? '',
                    '{{fecha_resolucion}}' => $fecha_resolucion,
                    '{{detalle}}'          => $memorandum->detalle      ?? '',
                    '{{evento}}'           => $memorandum->evento       ?? '',
                    '{{carrera}}'          => $memorandum->carrera      ?? '',
                    '{{departamento}}'     => $memorandum->departamento_mension ?? '',
                    '{{catedra}}'          => $memorandum->catedra      ?? '',
                ];

                $encabezadoFinal = $this->normalizarFuentesCert(strtr($memorandum->cert_encabezado_html ?? '', $vars));
                $tenorFinal      = $this->normalizarFuentesCert(strtr($memorandum->cert_tenor_html, $vars));

                // Obtener o crear MCertificado para esta persona
                $mcert = MCertificado::firstOrCreate(['persona_id' => $persona->id]);

                $qrData = "Codigo: {$mcert->id}\nNombre: {$persona->grado} {$persona->nombre} {$persona->paterno} {$persona->materno}\nEvento: {$memorandum->evento}\nDetalle: {$memorandum->detalle}\nTipo: {$persona->tipo}\nResolucion: {$memorandum->resolucion}\nFecha: {$fecha_resolucion}";
                $qrSvg  = QrCode::format('svg')->encoding('UTF-8')->size(155)->generate($qrData);
                $qr     = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

                $html = view('pdf.memo-cert', [
                    'mcert'              => $mcert,
                    'persona'            => $persona,
                    'qr'                 => $qr,
                    'fondoBase64'        => $fondoBase64,
                    'fontRobotoRegular'  => $fontRobotoRegular,
                    'fontRobotoSlab'     => $fontRobotoSlab,
                    'fontRobotoSlabBold' => $fontRobotoSlabBold,
                    'fontGreatVibes'     => $fontGreatVibes,
                    'encabezadoFinal'    => $encabezadoFinal,
                    'tenorFinal'         => $tenorFinal,
                    'generadoPor'        => auth()->user()?->name ?? 'Sistema',
                ])->render();

                $options = new Options();
                $options->set('isRemoteEnabled', false);
                $options->set('isHtml5ParserEnabled', true);
                $options->set('fontDir', public_path('fonts'));
                $options->set('fontCache', public_path('fonts'));
                $options->set('defaultFont', 'roboto slab');

                $dompdf = new Dompdf($options);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('letter', 'portrait');
                $dompdf->render();

                $destino  = "$directory/{$mcert->id}.pdf";
                $temporal = "$directory/{$mcert->id}_" . uniqid() . '.tmp';
                file_put_contents($temporal, $dompdf->output());
                if (file_exists($destino)) { @unlink($destino); }
                rename($temporal, $destino);
                $generados++;
                $certGenerados[] = [
                    'mcert_id' => $mcert->id,
                    'nombre'   => trim("{$persona->grado} {$persona->nombre} {$persona->paterno} {$persona->materno}"),
                    'tipo'     => $persona->tipo,
                ];

            } catch (\Exception $e) {
                $errores++;
                $detalleErrores[] = "ID {$persona->id}: " . $e->getMessage();
                \Illuminate\Support\Facades\Log::error("generarMasivoCert persona {$persona->id}: " . $e->getMessage());
                continue;
            } catch (\Throwable $e) {
                $errores++;
                $detalleErrores[] = "ID {$persona->id}: Error fatal - " . $e->getMessage();
                \Illuminate\Support\Facades\Log::error("generarMasivoCert persona {$persona->id} FATAL: " . $e->getMessage());
                continue;
            }
        }

        $mensaje = "Se generaron correctamente {$generados} certificado(s) en storage/app/certs-memo/{$memorandum_id}/";
        if ($errores > 0) {
            $mensaje .= " | Errores: {$errores}";
        }

        return redirect()->route('memorandums.show', $memorandum_id)
            ->with('success', $mensaje)
            ->with('detalleErrores', $detalleErrores);
    }

    private function normalizarFuentesCert(string $html): string
    {
        $html = str_replace('&quot;Roboto Slab&quot;', "'Roboto Slab'", $html);
        $html = str_replace('&quot;Great Vibes&quot;', "'Great Vibes'", $html);
        $html = str_replace('&quot;Roboto&quot;', "'Roboto'", $html);
        return $html;
    }

    private function fileToBase64Cert(string $path): string
    {
        if (!file_exists($path)) return '';
        $mime = mime_content_type($path);
        if (str_ends_with($path, '.ttf')) {
            $mime = 'font/truetype';
        }
        return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
    }
}

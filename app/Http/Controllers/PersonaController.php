<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Persona;
use Illuminate\Http\Request;
//pdf
use Dompdf\Dompdf;
use Dompdf\Options;
use Barryvdh\DomPDF\Facade as PDF;
use setasign\Fpdi\Tcpdf\Fpdi;
use Illuminate\Support\Facades\Storage;
//Generador de codigo QR
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon; //cambiar la fecha a texto
use COM;
use Faker\Provider\Person;

class PersonaController extends Controller
{
    public function index()
    {
        //
    }

    public function create(string $memorandum_id)
    {
        return view('personas.create', [
            'memorandum_id' => $memorandum_id
        ]);
    }

    public function store(Request $request)
    {
         $request->validate([
            'memorandum_id' => ['required'],
            //'codigo' => ['required'],
            'tipo' => ['required', 'string'],
            'carnet' => ['nullable'],
            'grado' => ['nullable'],
            'nombre' => ['required', 'string'],
            'paterno' => ['nullable'],
            'materno' => ['nullable'],
            'correo' => ['nullable', 'email'],
            'telefono' => ['nullable', 'string'],
        ]);

        $persona = Persona::create([
            'memorandum_id' => $request->input('memorandum_id'),
            //'codigo' => $request->input('codigo'),
            'tipo' => $request->input('tipo'),
            'carnet' => $request->input('carnet'),
            'grado' => $request->input('grado'),
            'nombre' => $request->input('nombre'),
            'paterno' => $request->input('paterno'),
            'materno' => $request->input('materno'),
            'correo' => $request->input('correo'),
            'telefono' => $request->input('telefono'),
            'estado' => 'enviado',
        ]);
        
        // Registrar en auditoría
        AuditLog::registrar(
            'crear_persona',
            Persona::class,
            $persona->id,
            null,
            $persona->toArray()
        );
        
        return to_route('memorandums.show', $request->input('memorandum_id'));
    }

    public function edit(string $id)
    {
        $persona = Persona::findOrFail($id);
        return view('personas.edit', [
            'persona' => $persona
        ]);
    }

    public function update(Request $request, string $id)
    {
         $request->validate([
            //'codigo' => ['required'],
            'tipo' => ['required', 'string'],
            'carnet' => ['nullable'],
            'grado' => ['nullable'],
            'nombre' => ['required', 'string'],
            'paterno' => ['nullable'],
            'materno' => ['nullable'],
            'correo' => ['nullable', 'email'],
            'telefono' => ['nullable', 'string'],
        ]);

        $persona = Persona::findOrFail($id);
        $datosAnteriores = $persona->toArray();
        
        $persona->update([
            'codigo' => $request->input('codigo'),
            'tipo' => $request->input('tipo'),
            'carnet' => $request->input('carnet'),
            'grado' => $request->input('grado'),
            'nombre' => $request->input('nombre'),
            'paterno' => $request->input('paterno'),
            'materno' => $request->input('materno'),
            'correo' => $request->input('correo'),
            'telefono' => $request->input('telefono'),
            'estado' => $request->input('estado', $persona->estado),
        ]);
        
        // Registrar en auditoría
        AuditLog::registrar(
            'actualizar_persona',
            Persona::class,
            $persona->id,
            $datosAnteriores,
            $persona->fresh()->toArray()
        );
        
        return to_route('memorandums.show', $persona->memorandum_id);
    }

    public function destroy(string $id)
    {
        $persona = Persona::findOrFail($id); // Encuentra el evento o lanza un error 404
        $datosAnteriores = $persona->toArray();
        $memorandumId = $persona->memorandum_id;
        
        $persona->delete(); // Elimina el evento
        
        // Registrar en auditoría
        AuditLog::registrar(
            'eliminar_persona',
            Persona::class,
            $id,
            $datosAnteriores,
            null
        );
        
        return to_route('memorandums.show', $memorandumId);
    }

        public function cambiarEstado(Request $request, string $id)
    {
        $request->validate([
            'estado' => ['required', 'in:enviado,anulado,aprobado'],
        ]);

        $persona = Persona::findOrFail($id);
        $datosAnteriores = $persona->toArray();

        $persona->update(['estado' => $request->input('estado')]);

        AuditLog::registrar(
            'cambiar_estado_persona',
            Persona::class,
            $persona->id,
            $datosAnteriores,
            $persona->fresh()->toArray()
        );

        return redirect()->route('memorandums.show', $persona->memorandum_id)
            ->with('success', 'Estado actualizado correctamente.');
    }

        public function certificado(string $id)
    {
        $persona = Persona::findOrFail($id);
        $memorandum = $persona->memorandum;

        Carbon::setLocale('es');
        $fecha_resolucion = Carbon::createFromFormat('Y-m-d', $memorandum->fecha_resolucion)
            ->translatedFormat('j \de F \de Y');
        $fecha_actual = Carbon::now()->locale('es')->translatedFormat('j \de F \de Y');

        // Reemplazar variables en los 4 bloques
        $vars = [
            '{{grado}}'           => $persona->grado ?? '',
            '{{nombre}}'          => $persona->nombre ?? '',
            '{{paterno}}'         => $persona->paterno ?? '',
            '{{materno}}'         => $persona->materno ?? '',
            '{{tipo}}'            => $persona->tipo ?? '',
            '{{resolucion}}'      => $memorandum->resolucion ?? '',
            '{{fecha_resolucion}}'=> $fecha_resolucion,
            '{{fecha_actual}}'    => $fecha_actual,
            '{{detalle}}'         => $memorandum->detalle ?? '',
            '{{evento}}'          => $memorandum->evento ?? '',
        ];

        $defaultEncabezado  = '<p><strong>UNIVERSIDAD MAYOR DE SAN ANDRÉS</strong></p><p>FACULTAD DE MEDICINA, ENFERMERÍA, NUTRICIÓN Y TECNOLOGÍA MÉDICA</p><p>VICEDECANATO</p>';
        $defaultCab         = '<p><strong>UNIVERSIDAD MAYOR DE SAN ANDRÉS</strong></p><p>FACULTAD DE MEDICINA, ENFERMERÍA,</p><p>NUTRICIÓN Y TECNOLOGÍA MÉDICA</p><p><strong>VICEDECANATO</strong></p>';
        $defaultDestinatario= '<p>Señor(a):</p><p>{{grado}} {{nombre}} {{paterno}} {{materno}}</p><p><strong>{{tipo}}</strong> - Facultad de Medicina, Enfermería, Nutrición y Tecnología Médica</p><p>Presente.-</p><p><br></p><p>{{fecha_actual}}</p>';
        $defaultTenor       = '<p>De mi consideración:</p><p>En cumplimiento a la <strong>{{resolucion}}</strong> de fecha {{fecha_resolucion}}, de la Facultad de Medicina, Enfermería, Nutrición y Tecnología Médica, comunico a usted que ha sido designado para conformar el <strong>{{detalle}}</strong> para la <strong>{{evento}}</strong>.</p><p>Deseandole éxitos en las funciones asignadas, saludo a usted muy cordialmente.</p><p>Atentamente,</p>';

        $encabezadoFinal   = strtr($memorandum->encabezado_html   ?? $defaultEncabezado,   $vars);
        $cabFinal          = strtr($memorandum->cab_html           ?? $defaultCab,          $vars);
        $destinatarioFinal = strtr($memorandum->destinatario_html  ?? $defaultDestinatario, $vars);
        $tenorFinal        = strtr($memorandum->tenor_html         ?? $defaultTenor,        $vars);

        // QR SVG base64
        $qrData = "Codigo: {$persona->id}\nNombre: {$persona->grado} {$persona->nombre} {$persona->paterno} {$persona->materno}\nEvento: {$memorandum->evento}\nDetalle: {$memorandum->detalle}\nTipo: {$persona->tipo}\nResolucion: {$memorandum->resolucion}\nFecha resolución: {$fecha_resolucion}";
        $qrSvg = QrCode::format('svg')->encoding('UTF-8')->size(175)->generate($qrData);
        $qr = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

        // Fuentes para Dompdf
        $fontRobotoRegular  = 'file://' . public_path('fonts/Roboto-Regular.ttf');
        $fontRobotoSlab     = 'file://' . public_path('fonts/Roboto-Slab.ttf');
        $fontRobotoSlabBold = 'file://' . public_path('fonts/RobotoSlab-Bold.ttf');
        $fontGreatVibes    = 'file://' . public_path('fonts/GreatVibes-Regular.ttf');

        // Imágenes en base64
        $fondoPath  = public_path('fondo-memo/fondomemo.jpg');
        $fondoBase64   = file_exists($fondoPath)  ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($fondoPath))  : '';

        $html = view("memos.1", [
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
            'fontGreatVibes'    => $fontGreatVibes,
            'fondoBase64'       => $fondoBase64,
            'generadoPor'       => auth()->user()?->name ?? 'Sistema',
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

        $dompdf->add_info('Title', $memorandum->evento);
        $dompdf->add_info('Author', 'Universidad Mayor de San Andrés - Facultad de Medicina');
        $dompdf->add_info('Subject', "Memorandum: {$persona->nombre} {$persona->paterno} {$persona->materno}");

        $directory = storage_path("app/memos/{$memorandum->id}");
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        file_put_contents(storage_path("app/memos/{$memorandum->id}/{$persona->id}.pdf"), $dompdf->output());

        AuditLog::registrar(
            'generar_memorandum',
            Persona::class,
            $persona->id,
            null,
            [
                'memorandum_id' => $memorandum->id,
                'persona_id'    => $persona->id,
                'nombre'        => trim("{$persona->grado} {$persona->nombre} {$persona->paterno} {$persona->materno}"),
                'tipo'          => $persona->tipo,
            ]
        );

        return response($dompdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="memo_' . $persona->id . '.pdf"');
    }
}

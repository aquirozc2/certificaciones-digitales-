<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\BusquedaController;
use App\Http\Controllers\CertificadoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\MemorandumController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ──────────────────────────────────────────────
//  AUTENTICACIÓN (públicas)
// ──────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

Route::post('logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ──────────────────────────────────────────────
//  RUTAS PROTEGIDAS (requieren autenticación)
// ──────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Redirigir según rol al dashboard correspondiente
    Route::get('/', function () {
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('dashboard');
        }
        if (auth()->user()->hasRole('operador')) {
            return redirect()->route('dashboard.operador');
        }
        return view('home');
    });

    // ── Dashboard de Administración (solo rol admin) ──
    Route::get('dashboard', [DashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware('role:admin');

    // ── Dashboard de Operador Académico (solo rol operador) ──
    Route::get('panel', [DashboardController::class, 'operador'])
        ->name('dashboard.operador')
        ->middleware('role:operador');

    // ── Búsqueda global ──
    Route::get('buscar', [BusquedaController::class, 'index'])->name('buscar');

    // ──────────────────────────────────────────────
    //  RUTAS PROTEGIDAS POR ROLES Y PERMISOS
    //  Middleware: role / permission (spatie/laravel-permission)
    // ──────────────────────────────────────────────

// ── Eventos ──
Route::middleware(['permission:eventos.ver'])->group(function () {
    Route::get('eventos', [EventoController::class, 'index'])->name('eventos.index');
});

Route::middleware(['permission:eventos.crear'])->group(function () {
    Route::get('eventos/create', [EventoController::class, 'create'])->name('eventos.create');
    Route::post('eventos', [EventoController::class, 'store'])->name('eventos.store');
});

Route::middleware(['permission:eventos.ver'])->group(function () {
    Route::get('eventos/{id}', [EventoController::class, 'show'])->name('eventos.show');
});

Route::middleware(['permission:eventos.editar'])->group(function () {
    Route::get('eventos/edit/{id}', [EventoController::class, 'edit'])->name('eventos.edit');
    Route::put('eventos/{id}', [EventoController::class, 'update'])->name('eventos.update');
});

Route::delete('eventos/{id}', [EventoController::class, 'destroy'])
    ->name('eventos.destroy')
    ->middleware('permission:eventos.eliminar');
    // Restricción adicional en EventoPolicy::delete()

// Rutas para diseño de certificados
Route::middleware(['permission:fondos.editar|fondos.crear'])->group(function () {
    Route::get('eventos/{id}/disenar-certificado', [EventoController::class, 'disenarCertificado'])->name('eventos.disenar');
    Route::post('eventos/{id}/previsualizar-diseno', [EventoController::class, 'previsualizarDiseno'])->name('eventos.previsualizarDiseno');
    Route::get('eventos/{id}/seleccionar-fondo', [EventoController::class, 'seleccionarFondo'])->name('eventos.seleccionarFondo');
    Route::post('eventos/{id}/guardar-fondo', [EventoController::class, 'guardarFondo'])->name('eventos.guardarFondo');
    Route::post('eventos/{id}/guardar-diseno', [EventoController::class, 'guardarDiseno'])->name('eventos.guardarDiseno');
});

// ── Certificados ──
Route::middleware(['permission:certificados.ver'])->group(function () {
    Route::get('certificados', [CertificadoController::class, 'index'])->name('certificados.index');
});

Route::middleware(['permission:certificados.crear'])->group(function () {
    Route::get('certificados/create/{evento_id}', [CertificadoController::class, 'create'])->name('certificados.create');
    Route::post('certificados', [CertificadoController::class, 'store'])->name('certificados.store');
});

Route::middleware(['permission:certificados.editar'])->group(function () {
    Route::get('certificados/edit/{id}', [CertificadoController::class, 'edit'])->name('certificados.edit');
    Route::put('certificados/{id}', [CertificadoController::class, 'update'])->name('certificados.update');
});

Route::delete('certificados/{id}', [CertificadoController::class, 'destroy'])
    ->name('certificados.destroy')
    ->middleware('permission:certificados.eliminar');

Route::middleware(['permission:certificados.generar_pdf'])->group(function () {
    Route::get('generar/{id}', [CertificadoController::class, 'certificado'])->name('certificados.certificado');
    Route::post('certificados/generar-masivo', [CertificadoController::class, 'generarMasivo'])->name('certificados.generarMasivo');
});

// ── Memorándums ──
Route::middleware(['permission:memorandums.ver'])->group(function () {
    Route::get('memorandums', [MemorandumController::class, 'index'])->name('memorandums.index');
});

Route::middleware(['permission:memorandums.crear'])->group(function () {
    Route::get('memorandums/create', [MemorandumController::class, 'create'])->name('memorandums.create');
    Route::post('memorandums', [MemorandumController::class, 'store'])->name('memorandums.store');
});

Route::middleware(['permission:memorandums.ver'])->group(function () {
    Route::get('memorandums/{id}', [MemorandumController::class, 'show'])->name('memorandums.show');
});

Route::middleware(['permission:memorandums.editar'])->group(function () {
    Route::get('memorandums/edit/{id}', [MemorandumController::class, 'edit'])->name('memorandums.edit');
    Route::put('memorandums/{id}', [MemorandumController::class, 'update'])->name('memorandums.update');
    Route::get('memorandums/{id}/disenar', [MemorandumController::class, 'disenar'])->name('memorandums.disenar');
    Route::post('memorandums/{id}/previsualizar-diseno', [MemorandumController::class, 'previsualizarDiseno'])->name('memorandums.previsualizarDiseno');
    Route::post('memorandums/{id}/guardar-diseno', [MemorandumController::class, 'guardarDiseno'])->name('memorandums.guardarDiseno');
    Route::post('memorandums/generar-masivo', [MemorandumController::class, 'generarMasivo'])->name('memorandums.generarMasivo');
    // Certificados desde memorandums
    Route::get('memorandums/{id}/seleccionar-fondo-cert', [MemorandumController::class, 'seleccionarFondoCert'])->name('memorandums.seleccionarFondoCert');
    Route::post('memorandums/{id}/guardar-fondo-cert', [MemorandumController::class, 'guardarFondoCert'])->name('memorandums.guardarFondoCert');
    Route::get('memorandums/{id}/disenar-cert', [MemorandumController::class, 'disenarCertificado'])->name('memorandums.disenarCert');
    Route::post('memorandums/{id}/previsualizar-diseno-cert', [MemorandumController::class, 'previsualizarDisenoCert'])->name('memorandums.previsualizarDisenoCert');
    Route::post('memorandums/{id}/guardar-diseno-cert', [MemorandumController::class, 'guardarDisenoCert'])->name('memorandums.guardarDisenoCert');
    Route::post('memorandums/generar-masivo-cert', [MemorandumController::class, 'generarMasivoCert'])->name('memorandums.generarMasivoCert');
});

Route::middleware(['permission:certificados.generar_pdf'])->group(function () {
    Route::get('memorandums/{memorandumId}/cert-persona/{personaId}', [MemorandumController::class, 'certPersona'])->name('memorandums.certPersona');
});

Route::delete('memorandums/{id}', [MemorandumController::class, 'destroy'])
    ->name('memorandums.destroy')
    ->middleware('permission:memorandums.eliminar');

// ── Personas / Participantes ──
Route::middleware(['permission:personas.crear'])->group(function () {
    Route::get('personas/create/{memorandum_id}', [PersonaController::class, 'create'])->name('personas.create');
    Route::post('personas', [PersonaController::class, 'store'])->name('personas.store');
});

Route::middleware(['permission:personas.editar'])->group(function () {
    Route::get('personas/edit/{id}', [PersonaController::class, 'edit'])->name('personas.edit');
    Route::put('personas/{id}', [PersonaController::class, 'update'])->name('personas.update');
    Route::patch('personas/{id}/estado', [PersonaController::class, 'cambiarEstado'])->name('personas.cambiarEstado');
});

Route::delete('personas/{id}', [PersonaController::class, 'destroy'])
    ->name('personas.destroy')
    ->middleware('permission:personas.eliminar');

Route::get('generarCert/{id}', [PersonaController::class, 'certificado'])
    ->name('personas.certificado')
    ->middleware('permission:certificados.generar_pdf');

// ── Reportes (solo admin) ──
Route::middleware(['role:admin'])->group(function () {
    Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('reportes/create', [ReporteController::class, 'create'])->name('reportes.create');
    Route::post('reportes', [ReporteController::class, 'store'])->name('reportes.store');
    Route::get('reportes/{reporte}/download', [ReporteController::class, 'download'])->name('reportes.download');
    Route::delete('reportes/{reporte}', [ReporteController::class, 'destroy'])->name('reportes.destroy');
});

// ── Usuarios (solo admin) ──
Route::middleware(['permission:usuarios.ver'])->group(function () {
    Route::get('usuarios', [UserController::class, 'index'])->name('usuarios.index');
});

Route::middleware(['permission:usuarios.crear'])->group(function () {
    Route::get('usuarios/create', [UserController::class, 'create'])->name('usuarios.create');
    Route::post('usuarios', [UserController::class, 'store'])->name('usuarios.store');
});

Route::middleware(['permission:usuarios.editar'])->group(function () {
    Route::get('usuarios/edit/{id}', [UserController::class, 'edit'])->name('usuarios.edit');
    Route::put('usuarios/{id}', [UserController::class, 'update'])->name('usuarios.update');
});

Route::delete('usuarios/{id}', [UserController::class, 'destroy'])
    ->name('usuarios.destroy')
    ->middleware('permission:usuarios.eliminar');

// ── Auditoría (solo admin con permiso auditoria.ver) ──
Route::middleware(['permission:auditoria.ver'])->group(function () {
    Route::get('auditoria', [AuditLogController::class, 'index'])->name('auditoria.index');
    Route::get('auditoria/{id}', [AuditLogController::class, 'show'])->name('auditoria.show');
});

}); // Fin middleware('auth')
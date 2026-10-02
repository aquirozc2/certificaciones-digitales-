<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Certificado;
use App\Models\Memorandum;
use App\Models\User;
use Carbon\Carbon;

/**
 * ──────────────────────────────────────────────────────────────
 *  DASHBOARD DE ADMINISTRACIÓN
 * ──────────────────────────────────────────────────────────────
 *
 *  VALIDACIÓN DE ROL:
 *  1) La ruta /dashboard aplica el middleware  role:admin  (definido
 *     en routes/web.php), por lo que solo usuarios con el rol 'admin'
 *     asignado mediante Spatie pueden acceder.
 *  2) Como segunda capa de seguridad, la propia vista Blade usa la
 *     directiva @role('admin') … @else … @endrole  para renderizar
 *     "Acceso Denegado" si de algún modo llega un usuario sin el rol.
 *
 *  De esta forma se cumple la lógica:
 *      if (user.role === 'admin')  →  mostrar dashboard
 *      else                        →  mostrar "Acceso Denegado"
 * ──────────────────────────────────────────────────────────────
 */
class DashboardController extends Controller
{
    /**
     * Mostrar el panel de control principal del sistema.
     *
     * Solo accesible para usuarios con rol 'admin' gracias al middleware
     * aplicado en la ruta (role:admin).
     */
    public function index()
    {
        // ── KPIs para las tarjetas del dashboard ──────────────

        // Total de certificados emitidos en el sistema
        $totalCertificados = Certificado::count();

        // Solicitudes (certificados) pendientes — los que aún no tienen enlace generado
        $solicitudesPendientes = Certificado::whereNull('enlace')->count();

        // Memorándums creados en los últimos 30 días
        $memosRecientes = Memorandum::where('created_at', '>=', Carbon::now()->subDays(30))
            ->count();

        // Usuarios activos del sistema (todos los registrados)
        $usuariosActivos = User::count();

        // ── Datos dinámicos para las cards ────────────────────

        // Últimos 5 memorándums creados (para Card "Solicitudes y Memorándums Recientes")
        $ultimosMemos = Memorandum::orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Últimos 5 certificados con enlace generado (validados)
        $ultimosCertificados = Certificado::whereNotNull('enlace')
            ->with('evento')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Certificados pendientes de validación (sin enlace)
        $certPendientes = Certificado::whereNull('enlace')
            ->with('evento')
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        // Últimas 5 actividades del log de auditoría
        $ultimasActividades = AuditLog::with('user')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // ── Renderizar la vista con los datos ─────────────────
        return view('dashboard.index', compact(
            'totalCertificados',
            'solicitudesPendientes',
            'memosRecientes',
            'usuariosActivos',
            'ultimosMemos',
            'ultimosCertificados',
            'certPendientes',
            'ultimasActividades',
        ));
    }

    /**
     * Dashboard para el rol Operador Académico.
     *
     * Muestra KPIs relevantes para la operación diaria:
     * certificados, memorándums, eventos y actividad reciente.
     */
    public function operador()
    {
        // ── KPIs ─────────────────────────────────────
        $totalCertificados     = Certificado::count();
        $totalMemorandums      = Memorandum::count();
        $certPendientes        = Certificado::whereNull('enlace')->count();
        $memosRecientes        = Memorandum::where('created_at', '>=', Carbon::now()->subDays(30))->count();

        // ── Últimos memorándums ──────────────────────
        $ultimosMemos = Memorandum::orderByDesc('created_at')
            ->limit(5)
            ->get();

        // ── Últimos certificados ─────────────────────
        $ultimosCertificados = Certificado::with('evento')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('dashboard.operador', compact(
            'totalCertificados',
            'totalMemorandums',
            'certPendientes',
            'memosRecientes',
            'ultimosMemos',
            'ultimosCertificados',
        ));
    }
}

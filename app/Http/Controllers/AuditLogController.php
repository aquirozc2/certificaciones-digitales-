<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AuditLogController extends Controller
{
    /**
     * Mostrar la lista de logs de auditoría.
     * Solo accesible para usuarios con permiso 'auditoria.ver'.
     */
    public function index(Request $request)
    {
        // Verificar permiso
        Gate::authorize('viewAny', AuditLog::class);

        // Construir query con filtros opcionales
        $query = AuditLog::with('user')
            ->orderBy('created_at', 'desc');

        // Filtro por acción
        if ($request->filled('accion')) {
            $query->where('accion', $request->accion);
        }

        // Filtro por entidad
        if ($request->filled('entidad')) {
            $query->where('entidad', 'LIKE', '%' . $request->entidad . '%');
        }

        // Filtro por usuario
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filtro por rango de fechas
        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        // Paginación
        $logs = $query->paginate(20)->withQueryString();

        // Obtener listas para filtros
        $acciones = AuditLog::select('accion')
            ->distinct()
            ->orderBy('accion')
            ->pluck('accion');

        $usuarios = \App\Models\User::select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('auditoria.index', compact('logs', 'acciones', 'usuarios'));
    }

    /**
     * Ver detalle de un log de auditoría específico.
     */
    public function show($id)
    {
        $log = AuditLog::with('user')->findOrFail($id);
        
        Gate::authorize('view', $log);

        return view('auditoria.show', compact('log'));
    }
}

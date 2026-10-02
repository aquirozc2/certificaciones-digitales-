<?php

namespace App\Policies;

use App\Models\Reporte;
use App\Models\User;

/**
 * Policy para el modelo Reporte.
 *
 * Controla el acceso a la generación y visualización de reportes.
 * Solo usuarios con rol ADMIN pueden acceder.
 */
class ReportePolicy
{
    /**
     * Determinar si el usuario puede ver el listado de reportes.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determinar si el usuario puede ver un reporte específico.
     */
    public function view(User $user, Reporte $reporte): bool
    {
        // Solo el admin puede ver, o el usuario que lo generó
        return $user->hasRole('admin') && ($user->id === $reporte->user_id || $user->hasRole('admin'));
    }

    /**
     * Determinar si el usuario puede crear reportes.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determinar si el usuario puede descargar un reporte.
     */
    public function download(User $user, Reporte $reporte): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determinar si el usuario puede eliminar un reporte.
     */
    public function delete(User $user, Reporte $reporte): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determinar si el usuario puede restaurar un reporte.
     */
    public function restore(User $user, Reporte $reporte): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determinar si el usuario puede eliminar permanentemente un reporte.
     */
    public function forceDelete(User $user, Reporte $reporte): bool
    {
        return $user->hasRole('admin');
    }
}

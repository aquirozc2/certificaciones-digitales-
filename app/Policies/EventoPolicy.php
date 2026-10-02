<?php

namespace App\Policies;

use App\Models\Evento;
use App\Models\User;

/**
 * Policy para el modelo Evento.
 *
 * Controla el acceso granular más allá de los permisos genéricos de Spatie.
 * Restricción clave: un Operador Académico NO puede eliminar un evento
 * que ya tenga certificados emitidos.
 */
class EventoPolicy
{
    /**
     * Determinar si el usuario puede ver el listado de eventos.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('eventos.ver');
    }

    /**
     * Determinar si el usuario puede ver un evento específico.
     */
    public function view(User $user, Evento $evento): bool
    {
        return $user->hasPermissionTo('eventos.ver');
    }

    /**
     * Determinar si el usuario puede crear eventos.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('eventos.crear');
    }

    /**
     * Determinar si el usuario puede actualizar un evento.
     */
    public function update(User $user, Evento $evento): bool
    {
        return $user->hasPermissionTo('eventos.editar');
    }

    /**
     * Determinar si el usuario puede eliminar un evento.
     *
     * ┌─────────────────────────────────────────────────────┐
     * │  RESTRICCIÓN ESTRICTA:                              │
     * │  Si el evento ya tiene certificados emitidos,       │
     * │  NINGÚN operador puede eliminarlo.                  │
     * │  Solo un admin podría hacerlo (y aun así            │
     * │  se recomienda auditar la acción).                  │
     * └─────────────────────────────────────────────────────┘
     */
    public function delete(User $user, Evento $evento): bool
    {
        // El usuario debe tener el permiso base
        if (! $user->hasPermissionTo('eventos.eliminar')) {
            return false;
        }

        // Si el evento tiene certificados asociados, bloquear al operador
        if ($evento->certificados()->exists()) {
            // Solo admin puede eliminar eventos con certificados
            // (se recomienda además registrar esto en audit_logs)
            return $user->hasRole('admin');
        }

        return true;
    }

    /**
     * Determinar si el usuario puede diseñar la plantilla del certificado
     * asociada a un evento.
     */
    public function disenar(User $user, Evento $evento): bool
    {
        return $user->hasAnyPermission(['fondos.crear', 'fondos.editar']);
    }
}

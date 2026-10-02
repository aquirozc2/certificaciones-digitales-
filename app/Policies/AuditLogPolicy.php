<?php

namespace App\Policies;

use App\Models\AuditLog;
use App\Models\User;

/**
 * Policy para AuditLog.
 *
 * Garantiza acceso de SOLO LECTURA. Ninguna acción de escritura
 * es permitida a ningún rol a través de la aplicación.
 * La inmutabilidad se refuerza en 3 capas:
 *  1. Esta Policy (capa HTTP/autorización)
 *  2. El modelo AuditLog (capa Eloquent)
 *  3. Trigger PostgreSQL (capa base de datos)
 */
class AuditLogPolicy
{
    /**
     * Solo usuarios con permiso 'auditoria.ver' pueden listar los logs.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('auditoria.ver');
    }

    /**
     * Solo usuarios con permiso 'auditoria.ver' pueden ver un log específico.
     */
    public function view(User $user, AuditLog $log): bool
    {
        return $user->hasPermissionTo('auditoria.ver');
    }

    /** Nadie puede crear manualmente desde la UI. */
    public function create(User $user): bool
    {
        return false;
    }

    /** Nadie puede actualizar un registro de auditoría. */
    public function update(User $user, AuditLog $log): bool
    {
        return false;
    }

    /** Nadie puede eliminar un registro de auditoría. */
    public function delete(User $user, AuditLog $log): bool
    {
        return false;
    }
}

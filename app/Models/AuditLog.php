<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo de log de auditoría inmutable.
 *
 * SEGURIDAD: Este modelo NO permite actualizaciones ni eliminaciones.
 * A nivel de BD (PostgreSQL) existe un trigger que bloquea UPDATE y DELETE.
 * A nivel de aplicación se sobreescriben los métodos de Eloquent.
 */
class AuditLog extends Model
{
    /** @var string */
    protected $table = 'audit_logs';

    /** Desactivar timestamps de updated_at (solo nos interesa created_at) */
    public const UPDATED_AT = null;

    /** @var list<string> */
    protected $fillable = [
        'user_id',
        'accion',
        'entidad',
        'entidad_id',
        'datos_anteriores',
        'datos_nuevos',
        'ip',
        'user_agent',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'datos_anteriores' => 'array',
            'datos_nuevos'     => 'array',
            'created_at'       => 'datetime',
        ];
    }

    // ──────────────────────────────────────────────
    //  BLOQUEO A NIVEL DE APLICACIÓN (defensa en profundidad)
    // ──────────────────────────────────────────────

    /**
     * Bloquear cualquier actualización desde Eloquent.
     */
    public function save(array $options = []): bool
    {
        if ($this->exists) {
            throw new \RuntimeException(
                'Los registros de auditoría son inmutables. No se permite actualizar.'
            );
        }

        return parent::save($options);
    }

    /**
     * Bloquear eliminación desde Eloquent.
     */
    public function delete(): ?bool
    {
        throw new \RuntimeException(
            'Los registros de auditoría son inmutables. No se permite eliminar.'
        );
    }

    /**
     * Bloquear eliminación forzada.
     */
    public function forceDelete(): ?bool
    {
        throw new \RuntimeException(
            'Los registros de auditoría son inmutables. No se permite eliminar.'
        );
    }

    // ──────────────────────────────────────────────
    //  RELACIONES
    // ──────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ──────────────────────────────────────────────
    //  HELPER PARA REGISTRAR ACCIONES
    // ──────────────────────────────────────────────

    /**
     * Registrar una entrada de auditoría de forma sencilla.
     */
    public static function registrar(
        string  $accion,
        string  $entidad,
        ?int    $entidadId = null,
        ?array  $datosAnteriores = null,
        ?array  $datosNuevos = null,
    ): static {
        return static::create([
            'user_id'          => auth()->id(),
            'accion'           => $accion,
            'entidad'          => $entidad,
            'entidad_id'       => $entidadId,
            'datos_anteriores' => $datosAnteriores,
            'datos_nuevos'     => $datosNuevos,
            'ip'               => request()->ip(),
            'user_agent'       => request()->userAgent(),
        ]);
    }
}

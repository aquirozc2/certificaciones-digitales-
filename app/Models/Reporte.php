<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reporte extends Model
{
    /** @var string */
    protected $table = 'reportes';

    /** @var list<string> */
    protected $fillable = [
        'user_id',
        'tipo_reporte',
        'criterios',
        'fecha_desde',
        'fecha_hasta',
        'formato',
        'ruta_archivo',
        'estado',
        'mensaje_error',
        'cantidad_registros',
        'tamaño_archivo',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'criterios'        => 'array',
            'fecha_desde'      => 'date',
            'fecha_hasta'      => 'date',
            'cantidad_registros' => 'integer',
            'tamaño_archivo'   => 'integer',
            'created_at'       => 'datetime',
            'updated_at'       => 'datetime',
        ];
    }

    // ──────────────────────────────────────────────
    //  RELACIONES
    // ──────────────────────────────────────────────

    /**
     * El usuario que generó el reporte.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ──────────────────────────────────────────────
    //  MÉTODOS ÚTILES
    // ──────────────────────────────────────────────

    /**
     * Tipos de reportes disponibles.
     */
    public static function tiposDisponibles(): array
    {
        return [
            'certificados_emitidos' => 'Certificados Emitidos',
            'uso_sistema' => 'Uso del Sistema por Usuario',
            'memorandums_departamento' => 'Memorándums por Departamento',
            'certificados_por_evento' => 'Certificados por Evento',
            'actividad_usuarios' => 'Actividad de Usuarios',
            'auditoria_sistema' => 'Auditoría del Sistema',
        ];
    }

    /**
     * Obtener descripción legible del tipo de reporte.
     */
    public function getTipoReporteDescripcion(): string
    {
        return self::tiposDisponibles()[$this->tipo_reporte] ?? $this->tipo_reporte;
    }

    /**
     * Formatos disponibles para descargar.
     */
    public static function formatosDisponibles(): array
    {
        return [
            'pdf' => 'PDF',
            'excel' => 'Excel',
            'csv' => 'CSV',
        ];
    }

    /**
     * Obtener descripción del formato.
     */
    public function getFormatoDescripcion(): string
    {
        return self::formatosDisponibles()[$this->formato] ?? $this->formato;
    }

    /**
     * Obtener ícono según el formato.
     */
    public function getIconoFormato(): string
    {
        return match ($this->formato) {
            'pdf' => 'bi-file-pdf-fill',
            'excel' => 'bi-file-earmark-spreadsheet-fill',
            'csv' => 'bi-file-text-fill',
            default => 'bi-file-earmark-fill',
        };
    }

    /**
     * Obtener color según el estado.
     */
    public function getColorEstado(): string
    {
        return match ($this->estado) {
            'generado' => '#22c55e',  // green
            'pendiente' => '#f59e0b', // amber
            'error' => '#ef4444',     // red
            default => '#6b7280',     // gray
        };
    }

    /**
     * Obtener descripción del estado.
     */
    public function getEstadoDescripcion(): string
    {
        return match ($this->estado) {
            'generado' => 'Generado',
            'pendiente' => 'Pendiente',
            'error' => 'Error',
            default => $this->estado,
        };
    }
}

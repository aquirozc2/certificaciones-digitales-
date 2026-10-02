<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificado extends Model
{
    protected $table = 'certificados';
    protected $fillable = [
        'evento_id',
        'codigo',
        'tipo',
        'carnet',
        'grado',
        'nombre',
        'paterno',
        'materno',
        'correo',
        'telefono',
        'enlace',
    ];
    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class,'evento_id');
    }
    
//Generar código incremental automáticamente por evento
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($certificado) {
            // Buscar el último código para el mismo evento
            $ultimoCodigo = self::where('evento_id', $certificado->evento_id)
                ->max('codigo');

            // Asignar el nuevo código (si no hay, empieza en 1)
            $certificado->codigo = $ultimoCodigo ? $ultimoCodigo + 1 : 1;
        });
    }
}
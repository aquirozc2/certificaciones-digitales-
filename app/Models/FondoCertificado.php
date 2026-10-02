<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FondoCertificado extends Model
{
    protected $table = 'fondos_certificados';
    
    protected $fillable = [
        'nombre',
        'codigo',
        'ruta_fondo',
        'orientacion',
        'descripcion',
        'orden',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function eventos(): HasMany
    {
        return $this->hasMany(Evento::class, 'fondo_id');
    }
}

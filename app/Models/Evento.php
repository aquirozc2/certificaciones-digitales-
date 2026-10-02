<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evento extends Model
{
    protected $table = 'eventos';
    protected $fillable = [
        'evento',
        'detalle',
        'fecha_inicio',
        'fecha_fin',
        'fondo_id',
        'encabezado_html',
        'tenor_html',
    ];
    
    public function certificados(): HasMany
    {
        return $this->hasMany(Certificado::class);
    }

    public function fondo(): BelongsTo
    {
        return $this->belongsTo(FondoCertificado::class, 'fondo_id');
    }
}

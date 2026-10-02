<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Memorandum extends Model
{
    protected $table = 'memorandums';
    protected $fillable = [
        'evento',
        'detalle',
        'resolucion',
        'fecha_resolucion',
        'carrera',
        'departamento_mension',
        'catedra',
        'encabezado_html',
        'cab_html',
        'destinatario_html',
        'tenor_html',
        'fondo_id',
        'cert_encabezado_html',
        'cert_tenor_html',
    ];

    public function personas()
    {
        return $this->hasMany(Persona::class);
    }

    public function fondo()
    {
        return $this->belongsTo(FondoCertificado::class, 'fondo_id');
    }
}

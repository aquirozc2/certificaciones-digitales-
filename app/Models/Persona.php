<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'personas';
    protected $fillable = [
        'memorandum_id',
        'codigo',
        'tipo',
        'grado',
        'carnet',
        'nombre',
        'paterno',
        'materno',
        'correo',
        'telefono',
        'enlace',
        'estado',
    ];

    public function memorandum()
    {
        return $this->belongsTo(Memorandum::class);
    }

    public function mcertificado()
    {
        return $this->hasOne(MCertificado::class);
    }
}

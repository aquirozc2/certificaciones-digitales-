<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MCertificado extends Model
{
    protected $table = 'mcertificados';

    protected $fillable = ['persona_id'];

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class);
    }
}

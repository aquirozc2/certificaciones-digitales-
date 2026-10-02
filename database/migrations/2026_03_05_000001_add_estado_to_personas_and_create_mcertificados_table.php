<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Agregar columna estado a personas
        Schema::table('personas', function (Blueprint $table) {
            $table->enum('estado', ['enviado', 'anulado', 'aprobado'])
                  ->default('enviado')
                  ->after('enlace');
        });

        // 2. Crear tabla mcertificados
        Schema::create('mcertificados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')
                  ->constrained('personas')
                  ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mcertificados');

        Schema::table('personas', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }
};

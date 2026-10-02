<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fondos_certificados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // "Vertical 1 Firma", "Horizontal 2 Firmas", etc.
            $table->string('codigo')->unique(); // fondo1, fondo2, etc.
            $table->string('ruta_fondo'); // ruta del fondo completo
            $table->enum('orientacion', ['portrait', 'landscape'])->default('portrait');
            $table->string('descripcion')->nullable(); // "Vertical con una firma al centro"
            $table->integer('orden')->default(0); // para ordenar en la galería
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fondos_certificados');
    }
};

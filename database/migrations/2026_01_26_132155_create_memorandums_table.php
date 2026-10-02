<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('memorandums', function (Blueprint $table) {
            $table->id();
            $table->string('evento');
            $table->text('detalle')->nullable();
            $table->string('resolucion')->nullable();
            $table->date('fecha_resolucion')->nullable();
            $table->string('carrera')->nullable();
            $table->string('departamento_mension')->nullable();
            $table->string('catedra')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memorandums');
    }
};

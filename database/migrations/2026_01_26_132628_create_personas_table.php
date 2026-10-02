<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('memorandum_id');
            $table->foreign('memorandum_id')->references('id')->on('memorandums')->onDelete('cascade');
            $table->integer('codigo')->nullable();
            $table->string('tipo')->nullable();
            $table->string('grado')->nullable();
            $table->string('carnet')->nullable();
            $table->string('nombre');
            $table->string('paterno')->nullable();
            $table->string('materno')->nullable();
            $table->string('correo')->nullable();
            $table->string('telefono')->nullable();
            $table->string('enlace')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reportes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            
            // Tipo de reporte: certificados_emitidos, uso_sistema, memorandums_departamento, etc.
            $table->string('tipo_reporte');
            
            // Criterios de búsqueda en JSON para flexibilidad
            $table->json('criterios')->nullable();
            
            // Rango de fechas
            $table->date('fecha_desde')->nullable();
            $table->date('fecha_hasta')->nullable();
            
            // Formato del reporte generado: pdf, excel, csv
            $table->string('formato')->default('pdf');
            
            // Ruta del archivo generado
            $table->string('ruta_archivo')->nullable();
            
            // Estado del reporte: generado, pendiente, error
            $table->string('estado')->default('generado');
            
            // Descripción de error si aplica
            $table->text('mensaje_error')->nullable();
            
            // Cantidad de registros incluidos en el reporte
            $table->integer('cantidad_registros')->default(0);
            
            // Tamaño del archivo en bytes
            $table->bigInteger('tamaño_archivo')->nullable();
            
            $table->timestamps();
            
            // Índices para búsquedas frecuentes
            $table->index('user_id');
            $table->index('tipo_reporte');
            $table->index('created_at');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportes');
    }
};

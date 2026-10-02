<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->unsignedBigInteger('fondo_id')->nullable()->after('fecha_fin');
            $table->foreign('fondo_id')->references('id')->on('fondos_certificados')->onDelete('set null');
            $table->text('encabezado_html')->nullable()->after('fondo_id');
            $table->text('tenor_html')->nullable()->after('encabezado_html');
        });
    }

    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropForeign(['fondo_id']);
            $table->dropColumn(['fondo_id', 'encabezado_html', 'tenor_html']);
        });
    }
};

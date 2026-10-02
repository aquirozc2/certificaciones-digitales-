<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('memorandums', function (Blueprint $table) {
            $table->unsignedBigInteger('fondo_id')->nullable()->after('catedra');
            $table->text('cert_encabezado_html')->nullable()->after('fondo_id');
            $table->text('cert_tenor_html')->nullable()->after('cert_encabezado_html');

            $table->foreign('fondo_id')->references('id')->on('fondos_certificados')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('memorandums', function (Blueprint $table) {
            $table->dropForeign(['fondo_id']);
            $table->dropColumn(['fondo_id', 'cert_encabezado_html', 'cert_tenor_html']);
        });
    }
};

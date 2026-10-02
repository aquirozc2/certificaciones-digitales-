<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('memorandums', function (Blueprint $table) {
            $table->text('encabezado_html')->nullable()->after('catedra');
            $table->text('cab_html')->nullable()->after('encabezado_html');
            $table->text('destinatario_html')->nullable()->after('cab_html');
            $table->text('tenor_html')->nullable()->after('destinatario_html');
        });
    }

    public function down(): void
    {
        Schema::table('memorandums', function (Blueprint $table) {
            $table->dropColumn(['encabezado_html', 'cab_html', 'destinatario_html', 'tenor_html']);
        });
    }
};

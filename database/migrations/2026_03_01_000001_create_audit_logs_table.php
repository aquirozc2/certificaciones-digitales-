<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla de auditoría inmutable.
     * Incluye un trigger PostgreSQL que impide UPDATE y DELETE.
     */
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->string('accion', 50);              // crear, actualizar, eliminar, login, etc.
            $table->string('entidad', 100);             // App\Models\Evento, etc.
            $table->unsignedBigInteger('entidad_id')->nullable();
            $table->jsonb('datos_anteriores')->nullable();
            $table->jsonb('datos_nuevos')->nullable();
            $table->ipAddress('ip')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();

            // Índices para consultas de auditoría
            $table->index('user_id');
            $table->index('accion');
            $table->index('entidad');
            $table->index('created_at');
        });

        // ──────────────────────────────────────────────
        //  TRIGGER PostgreSQL: Inmutabilidad a nivel de BD
        //  Impide UPDATE y DELETE incluso con acceso directo a la BD
        // ──────────────────────────────────────────────
        if (DB::getDriverName() === 'pgsql') {
            DB::unprepared(<<<'SQL'
                CREATE OR REPLACE FUNCTION bloquear_modificacion_audit_logs()
                RETURNS TRIGGER AS $$
                BEGIN
                    RAISE EXCEPTION 'Los registros de audit_logs son inmutables. Operación % bloqueada.', TG_OP;
                    RETURN NULL;
                END;
                $$ LANGUAGE plpgsql;

                CREATE TRIGGER trg_audit_logs_inmutable
                    BEFORE UPDATE OR DELETE ON audit_logs
                    FOR EACH ROW
                    EXECUTE FUNCTION bloquear_modificacion_audit_logs();
            SQL);

            // Revocar permisos de UPDATE/DELETE al rol de aplicación.
            // Ajustar 'certificados_app' al nombre real de tu usuario de BD.
            // DB::unprepared("REVOKE UPDATE, DELETE ON audit_logs FROM certificados_app;");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::unprepared('DROP TRIGGER IF EXISTS trg_audit_logs_inmutable ON audit_logs;');
            DB::unprepared('DROP FUNCTION IF EXISTS bloquear_modificacion_audit_logs();');
        }

        Schema::dropIfExists('audit_logs');
    }
};

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Seeder de Roles y Permisos para el Sistema de Certificaciones Digitales.
 *
 * Roles:
 *  1. admin     → Administrador del sistema (SysAdmin)
 *  2. operador  → Operador Académico (Kárdex / Administrativo)
 *
 * Convención de nomenclatura: <entidad>.<acción>
 */
class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar caché de permisos de Spatie antes de sembrar
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ──────────────────────────────────────────────
        //  1. DEFINICIÓN DE PERMISOS
        // ──────────────────────────────────────────────

        $permisos = [
            // ── Catálogos (Carreras, Departamentos, etc.) ──
            'catalogos.ver',
            'catalogos.crear',
            'catalogos.editar',
            'catalogos.eliminar',
            'catalogos.gestionar',          // Atajo: CRUD completo de catálogos

            // ── Eventos ──
            'eventos.ver',
            'eventos.crear',
            'eventos.editar',
            'eventos.eliminar',

            // ── Certificados ──
            'certificados.ver',
            'certificados.crear',
            'certificados.editar',
            'certificados.eliminar',
            'certificados.generar_pdf',     // Generar PDF en estado borrador
            'certificados.emitir',          // Cambiar estado a emitido (firma final)

            // ── Memorándums ──
            'memorandums.ver',
            'memorandums.crear',
            'memorandums.editar',
            'memorandums.eliminar',

            // ── Personas / Participantes ──
            'personas.ver',
            'personas.crear',
            'personas.editar',
            'personas.eliminar',
            'personas.importar',            // Subir listas masivas (Excel)

            // ── Fondos de certificado / Plantillas ──
            'fondos.ver',
            'fondos.crear',
            'fondos.editar',
            'fondos.eliminar',

            // ── Usuarios y Roles ──
            'usuarios.ver',
            'usuarios.crear',
            'usuarios.editar',
            'usuarios.eliminar',
            'roles.asignar',

            // ── Auditoría / Bitácora ──
            'auditoria.ver',                // Solo lectura de audit_logs

            // ── Métricas / Dashboard ──
            'metricas.ver',

            // ── Configuración del sistema ──
            'configuracion.gestionar',      // Servidor, backups, parámetros globales
        ];

        // Crear todos los permisos (guard: web)
        foreach ($permisos as $permiso) {
            Permission::firstOrCreate([
                'name'       => $permiso,
                'guard_name' => 'web',
            ]);
        }

        // ──────────────────────────────────────────────
        //  2. CREACIÓN DE ROLES Y ASIGNACIÓN DE PERMISOS
        // ──────────────────────────────────────────────

        // ── Admin / Administrador del sistema ──
        // Gestiona catálogos, usuarios, roles y configuración del servidor.
        // RESTRICCIÓN: NO puede alterar audit_logs (solo ver).
        $admin = Role::firstOrCreate([
            'name'       => 'admin',
            'guard_name' => 'web',
        ]);

        $admin->syncPermissions([
            'catalogos.ver',
            'catalogos.crear',
            'catalogos.editar',
            'catalogos.eliminar',
            'catalogos.gestionar',
            'usuarios.ver',
            'usuarios.crear',
            'usuarios.editar',
            'usuarios.eliminar',
            'roles.asignar',
            'configuracion.gestionar',
            'auditoria.ver',               // Solo lectura — NO escritura
            'metricas.ver',
            // Acceso de lectura a módulos operativos (supervisión)
            'eventos.ver',
            'certificados.ver',
            'memorandums.ver',
            'personas.ver',
            'fondos.ver',
        ]);

        // ── Operador Académico ──
        // Registra eventos, sube participantes, diseña plantillas,
        // genera PDFs en estado "Borrador".
        // RESTRICCIÓN: NO puede eliminar eventos con certificados emitidos
        //              (se maneja en EventoPolicy).
        $operador = Role::firstOrCreate([
            'name'       => 'operador',
            'guard_name' => 'web',
        ]);

        $operador->syncPermissions([
            'eventos.ver',
            'eventos.crear',
            'eventos.editar',
            'eventos.eliminar',             // Condicionado por EventoPolicy
            'certificados.ver',
            'certificados.crear',
            'certificados.editar',
            'certificados.generar_pdf',
            'memorandums.ver',
            'memorandums.crear',
            'memorandums.editar',
            'memorandums.eliminar',
            'personas.ver',
            'personas.crear',
            'personas.editar',
            'personas.eliminar',
            'personas.importar',
            'fondos.ver',
            'fondos.crear',
            'fondos.editar',
        ]);
    }
}

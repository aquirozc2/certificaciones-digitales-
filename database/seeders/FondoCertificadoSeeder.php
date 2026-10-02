<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FondoCertificadoSeeder extends Seeder
{
    public function run(): void
    {
        // Evitar duplicados si ya existen
        if (DB::table('fondos_certificados')->count() > 0) {
            return;
        }

        DB::table('fondos_certificados')->insert([
            [
                'nombre'      => 'Fondo Vertical 1',
                'codigo'      => 'fondo1',
                'ruta_fondo'  => 'fondo-completos/fondo1.jpg',
                'orientacion' => 'portrait',
                'descripcion' => 'Fondo vertical diseño 1',
                'orden'       => 1,
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'nombre'      => 'Fondo Vertical 2',
                'codigo'      => 'fondo2',
                'ruta_fondo'  => 'fondo-completos/fondo2.jpg',
                'orientacion' => 'portrait',
                'descripcion' => 'Fondo vertical diseño 2',
                'orden'       => 2,
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'nombre'      => 'Fondo Vertical 3',
                'codigo'      => 'fondo3',
                'ruta_fondo'  => 'fondo-completos/fondo3.jpg',
                'orientacion' => 'portrait',
                'descripcion' => 'Fondo vertical diseño 3',
                'orden'       => 3,
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'nombre'      => 'Fondo Horizontal 1',
                'codigo'      => 'fondo4',
                'ruta_fondo'  => 'fondo-completos/fondo4Horizontal.jpg',
                'orientacion' => 'landscape',
                'descripcion' => 'Fondo horizontal diseño 1',
                'orden'       => 4,
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'nombre'      => 'Fondo Horizontal 2',
                'codigo'      => 'fondo5',
                'ruta_fondo'  => 'fondo-completos/fondo5Horizontal.jpg',
                'orientacion' => 'landscape',
                'descripcion' => 'Fondo horizontal diseño 2',
                'orden'       => 5,
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'nombre'      => 'Fondo Horizontal 3',
                'codigo'      => 'fondo6',
                'ruta_fondo'  => 'fondo-completos/fondo6Horizontal.jpg',
                'orientacion' => 'landscape',
                'descripcion' => 'Fondo horizontal diseño 3',
                'orden'       => 6,
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}

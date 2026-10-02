<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Roles y permisos SIEMPRE primero
        $this->call(RolesAndPermissionsSeeder::class);

        // Fondos de certificado
        $this->call(FondoCertificadoSeeder::class);

        // Crear las cuentas iniciales desde la consola, sin contraseñas públicas.
        if (!$this->command || !app()->runningInConsole()) {
            return;
        }
        foreach (['sysadmin' => 'admin', 'operador' => 'operador'] as $username => $role) {
            if (User::where('username', $username)->exists()) {
                continue;
            }
            $password = $this->command->secret("Contraseña para {$username} (mínimo 12 caracteres; vacío omite la cuenta)");
            if (!$password) {
                continue;
            }
            if (strlen($password) < 12) {
                throw new \RuntimeException('La contraseña debe tener al menos 12 caracteres.');
            }
            $user = User::create([
                'username' => $username,
                'name' => $username,
                'email' => $username . '@example.com',
                'password' => bcrypt($password),
            ]);
            $user->syncRoles($role);
        }
    }
}

<?php

namespace App\Providers;

use App\Models\AuditLog;
use App\Models\Evento;
use App\Policies\AuditLogPolicy;
use App\Policies\EventoPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ── Registrar Policies ──
        Gate::policy(Evento::class, EventoPolicy::class);
        Gate::policy(AuditLog::class, AuditLogPolicy::class);

        // ── Admin: bypass implícito de todas las Gates ──
        // EXCEPTO las de AuditLog (para respetar la inmutabilidad)
        Gate::before(function ($user, $ability) {
            // Si la gate involucra audit_logs, no hacer bypass
            if (str_starts_with($ability, 'audit')) {
                return null; // Dejar que la Policy decida
            }

            if ($user->hasRole('admin')) {
                return true;
            }

            return null;
        });
    }
}

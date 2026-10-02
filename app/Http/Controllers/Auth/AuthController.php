<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Mostrar el formulario de login.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Procesar el intento de autenticación.
     * Usa 'username' en lugar de 'email' como campo de identificación.
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'username.required' => 'El campo usuario es obligatorio.',
            'username.max'      => 'El usuario no puede exceder 50 caracteres.',
            'password.required' => 'El campo contraseña es obligatorio.',
            'password.min'      => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Registrar en auditoría
            AuditLog::registrar(
                accion: 'login',
                entidad: 'App\\Models\\User',
                entidadId: Auth::id(),
            );

            return redirect()->intended('/');
        }

        return back()
            ->withInput($request->only('username'))
            ->with('error', 'Las credenciales ingresadas no son válidas.');
    }

    /**
     * Cerrar sesión.
     */
    public function logout(Request $request): RedirectResponse
    {
        $userId = Auth::id();

        AuditLog::registrar(
            accion: 'logout',
            entidad: 'App\\Models\\User',
            entidadId: $userId,
        );

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

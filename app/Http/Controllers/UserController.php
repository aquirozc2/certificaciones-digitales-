<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $usuarios = User::with('roles')->get();
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::all();
        return view('usuarios.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        $usuario = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Asignar rol
        $usuario->assignRole($validated['role']);
        
        // Registrar en auditoría
        AuditLog::registrar(
            'crear_usuario',
            User::class,
            $usuario->id,
            null,
            array_merge($usuario->toArray(), ['rol' => $validated['role']])
        );

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        $roles = Role::all();
        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);
        $datosAnteriores = array_merge($usuario->toArray(), ['roles' => $usuario->roles->pluck('name')->toArray()]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        $usuario->update([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
        ]);

        // Actualizar contraseña solo si se proporcionó
        if ($request->filled('password')) {
            $usuario->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        // Sincronizar rol
        $usuario->syncRoles([$validated['role']]);
        
        // Registrar en auditoría
        AuditLog::registrar(
            'actualizar_usuario',
            User::class,
            $usuario->id,
            $datosAnteriores,
            array_merge($usuario->fresh()->toArray(), ['roles' => $usuario->roles->pluck('name')->toArray()])
        );

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);

        // No permitir eliminar al propio usuario
        if ($usuario->id === auth()->id()) {
            return redirect()->route('usuarios.index')
                ->with('error', 'No puedes eliminar tu propia cuenta.');
        }
        
        $datosAnteriores = array_merge($usuario->toArray(), ['roles' => $usuario->roles->pluck('name')->toArray()]);

        $usuario->delete();
        
        // Registrar en auditoría
        AuditLog::registrar(
            'eliminar_usuario',
            User::class,
            $id,
            $datosAnteriores,
            null
        );

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }
}

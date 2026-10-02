@extends('layouts.app')
@php $activePage = 'usuarios'; @endphp

@section('title', 'Modificar Usuario')

@section('content')

{{-- Breadcrumb --}}
<nav class="breadcrumb-custom mb-4">
    <a href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <span class="sep"><i class="bi bi-chevron-right"></i></span>
    <a href="{{ route('usuarios.index') }}"><i class="bi bi-people"></i> Usuarios</a>
    <span class="sep"><i class="bi bi-chevron-right"></i></span>
    <span>Editar: {{ Str::limit($usuario->name, 20) }}</span>
</nav>

<div class="row justify-content-center">
    <div class="col-lg-9 col-xl-8">
        <div class="page-card">
            <div class="page-card-head">
                <h2><i class="bi bi-pencil-square" style="color:#16a34a;"></i> Modificar Usuario</h2>
            </div>
            <div class="page-card-body">
                <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label-custom">Nombre Completo <span style="color:#dc2626;">*</span></label>
                            <input type="text" name="name" id="name" class="form-control-custom" 
                                   placeholder="Ej: Juan Pérez López" 
                                   value="{{ old('name', $usuario->name) }}" required>
                            <div class="form-hint">Nombre completo del usuario</div>
                            @error('name')
                                <div style="color:#dc2626;font-size:.72rem;margin-top:.2rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="username" class="form-label-custom">Nombre de Usuario <span style="color:#dc2626;">*</span></label>
                            <input type="text" name="username" id="username" class="form-control-custom" 
                                   placeholder="Ej: jperez" 
                                   value="{{ old('username', $usuario->username) }}" required>
                            <div class="form-hint">Usuario para iniciar sesión (único)</div>
                            @error('username')
                                <div style="color:#dc2626;font-size:.72rem;margin-top:.2rem;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label-custom">Correo Electrónico <span style="color:#dc2626;">*</span></label>
                            <input type="email" name="email" id="email" class="form-control-custom" 
                                   placeholder="usuario@ejemplo.com" 
                                   value="{{ old('email', $usuario->email) }}" required>
                            <div class="form-hint">Correo institucional o personal</div>
                            @error('email')
                                <div style="color:#dc2626;font-size:.72rem;margin-top:.2rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="role" class="form-label-custom">Rol del Usuario <span style="color:#dc2626;">*</span></label>
                            <select name="role" id="role" class="form-control-custom" required>
                                <option value="">-- Seleccionar rol --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" 
                                        {{ old('role', $usuario->roles->first()?->name) == $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-hint">Nivel de acceso en el sistema</div>
                            @error('role')
                                <div style="color:#dc2626;font-size:.72rem;margin-top:.2rem;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div style="background:#fefce8;border:1px solid #fde047;border-radius:8px;padding:.85rem 1rem;margin-top:1.25rem;">
                        <div style="display:flex;gap:.5rem;align-items:start;">
                            <i class="bi bi-shield-lock-fill" style="color:#854d0e;font-size:1.1rem;"></i>
                            <div style="font-size:.8rem;color:#854d0e;">
                                <strong>Cambiar contraseña (opcional):</strong> Solo completa estos campos si deseas cambiar la contraseña del usuario. Si los dejas vacíos, se mantendrá la contraseña actual.
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label-custom">Nueva Contraseña <span style="color:#94a3b8;">(opcional)</span></label>
                            <input type="password" name="password" id="password" class="form-control-custom" 
                                   placeholder="Dejar vacío para no cambiar">
                            <div class="form-hint">Mínimo 8 caracteres si se desea cambiar</div>
                            @error('password')
                                <div style="color:#dc2626;font-size:.72rem;margin-top:.2rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label-custom">Confirmar Nueva Contraseña</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="form-control-custom" placeholder="Repetir nueva contraseña">
                            <div class="form-hint">Debe coincidir con la nueva contraseña</div>
                        </div>
                    </div>

                    @if($usuario->id === auth()->id())
                    <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:8px;padding:.85rem 1rem;margin-top:1.25rem;">
                        <div style="display:flex;gap:.5rem;align-items:start;">
                            <i class="bi bi-exclamation-triangle-fill" style="color:#991b1b;font-size:1.1rem;"></i>
                            <div style="font-size:.8rem;color:#991b1b;">
                                <strong>Atención:</strong> Estás editando tu propia cuenta. Los cambios afectarán tu sesión actual.
                            </div>
                        </div>
                    </div>
                    @endif

                    <div style="border-top:1px solid #f1f5f9;padding-top:1rem;margin-top:1.5rem;display:flex;gap:.6rem;justify-content:flex-end;">
                        <a href="{{ route('usuarios.index') }}" class="btn-secondary-custom" style="display:inline-flex;align-items:center;gap:.3rem;">
                            <i class="bi bi-x-lg"></i> Cancelar
                        </a>
                        <button type="submit" class="btn-success-custom" style="display:inline-flex;align-items:center;gap:.3rem;">
                            <i class="bi bi-check-lg"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

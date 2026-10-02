@extends('layouts.app')
@php $activePage = 'usuarios'; @endphp

@section('title', 'Registrar Nuevo Usuario')

@section('content')

{{-- Breadcrumb --}}
<nav class="breadcrumb-custom mb-4">
    <a href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <span class="sep"><i class="bi bi-chevron-right"></i></span>
    <a href="{{ route('usuarios.index') }}"><i class="bi bi-people"></i> Usuarios</a>
    <span class="sep"><i class="bi bi-chevron-right"></i></span>
    <span>Nuevo</span>
</nav>

<div class="row justify-content-center">
    <div class="col-lg-9 col-xl-8">
        <div class="page-card">
            <div class="page-card-head">
                <h2><i class="bi bi-person-plus-fill" style="color:#1e3a8a;"></i> Registrar Nuevo Usuario</h2>
            </div>
            <div class="page-card-body">
                <form action="{{ route('usuarios.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label-custom">Nombre Completo <span style="color:#dc2626;">*</span></label>
                            <input type="text" name="name" id="name" class="form-control-custom" 
                                   placeholder="Ej: Juan Pérez López" value="{{ old('name') }}" required>
                            <div class="form-hint">Nombre completo del usuario</div>
                            @error('name')
                                <div style="color:#dc2626;font-size:.72rem;margin-top:.2rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="username" class="form-label-custom">Nombre de Usuario <span style="color:#dc2626;">*</span></label>
                            <input type="text" name="username" id="username" class="form-control-custom" 
                                   placeholder="Ej: jperez" value="{{ old('username') }}" required>
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
                                   placeholder="usuario@ejemplo.com" value="{{ old('email') }}" required>
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
                                    <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
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

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label-custom">Contraseña <span style="color:#dc2626;">*</span></label>
                            <input type="password" name="password" id="password" class="form-control-custom" 
                                   placeholder="Mínimo 8 caracteres" required>
                            <div class="form-hint">Mínimo 8 caracteres</div>
                            @error('password')
                                <div style="color:#dc2626;font-size:.72rem;margin-top:.2rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label-custom">Confirmar Contraseña <span style="color:#dc2626;">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="form-control-custom" placeholder="Repetir contraseña" required>
                            <div class="form-hint">Debe coincidir con la contraseña</div>
                        </div>
                    </div>

                    <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:.85rem 1rem;margin-top:1.25rem;">
                        <div style="display:flex;gap:.5rem;align-items:start;">
                            <i class="bi bi-info-circle-fill" style="color:#1e40af;font-size:1.1rem;"></i>
                            <div style="font-size:.8rem;color:#1e40af;">
                                <strong>Información sobre roles:</strong>
                                <ul style="margin:.3rem 0 0;padding-left:1.2rem;">
                                    <li><strong>Admin:</strong> Acceso completo al sistema (gestión de usuarios, configuración)</li>
                                    <li><strong>Operador:</strong> Gestión de certificados y memorándums (sin acceso a configuración)</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div style="border-top:1px solid #f1f5f9;padding-top:1rem;margin-top:1.5rem;display:flex;gap:.6rem;justify-content:flex-end;">
                        <a href="{{ route('usuarios.index') }}" class="btn-secondary-custom" style="display:inline-flex;align-items:center;gap:.3rem;">
                            <i class="bi bi-x-lg"></i> Cancelar
                        </a>
                        <button type="submit" class="btn-primary-custom" style="display:inline-flex;align-items:center;gap:.3rem;">
                            <i class="bi bi-check-lg"></i> Registrar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@extends('layouts.app')
@php $activePage = 'usuarios'; @endphp

@section('title', 'Gestión de Usuarios')

@section('styles')
<style>
    .users-header {
        background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 100%);
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        color: #fff;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(30,58,138,.25);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }
    .users-header h2 {
        font-size: 1.2rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .users-table-wrap {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,.07);
        overflow: hidden;
    }
    .users-table-wrap .table-responsive {
        overflow-x: auto;
    }
    table.users-table {
        width: 100%;
        border-collapse: collapse;
        font-size: .85rem;
    }
    table.users-table thead tr {
        background: #1e3a8a;
        color: #fff;
    }
    table.users-table thead th {
        padding: .7rem 1rem;
        font-weight: 600;
        letter-spacing: .03em;
        white-space: nowrap;
        border: none;
    }
    table.users-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: background .12s;
    }
    table.users-table tbody tr:hover {
        background: #f8faff;
    }
    table.users-table tbody td {
        padding: .65rem 1rem;
        color: #374151;
        vertical-align: middle;
    }

    .role-badge {
        display: inline-block;
        padding: .25rem .65rem;
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .03em;
    }
    .role-badge.admin {
        background: #fee2e2;
        color: #991b1b;
    }
    .role-badge.operador {
        background: #dbeafe;
        color: #1e40af;
    }

    .tbl-btn {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .3rem .7rem;
        border-radius: 6px;
        font-size: .78rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        text-decoration: none;
        transition: filter .15s;
        white-space: nowrap;
        line-height: 1.4;
    }
    .tbl-btn:hover {
        filter: brightness(1.1);
    }
    .tbl-btn.green {
        background: #16a34a;
        color: #fff;
    }
    .tbl-btn.red {
        background: #dc2626;
        color: #fff;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #94a3b8;
    }
    .empty-state i {
        font-size: 2.5rem;
        display: block;
        margin-bottom: .75rem;
    }
    .empty-state p {
        font-size: .9rem;
        margin: 0;
    }
</style>
@endsection

@section('content')

{{-- Breadcrumb --}}
<nav class="breadcrumb-custom mb-4">
    <a href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <span class="sep"><i class="bi bi-chevron-right"></i></span>
    <span>Usuarios</span>
</nav>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-radius:10px;font-size:.875rem;">
    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-radius:10px;font-size:.875rem;">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Header --}}
<div class="users-header">
    <h2>
        <i class="bi bi-people-fill"></i>
        Gestión de Usuarios del Sistema
    </h2>
    @can('usuarios.crear')
    <a href="{{ route('usuarios.create') }}" class="btn-primary-custom" style="display:inline-flex;align-items:center;gap:.45rem;">
        <i class="bi bi-person-plus-fill"></i> Nuevo Usuario
    </a>
    @endcan
</div>

{{-- Users table --}}
<div class="users-table-wrap">
    <div class="table-responsive">
        <table class="users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NOMBRE</th>
                    <th>USERNAME</th>
                    <th>CORREO</th>
                    <th>ROL</th>
                    <th>REGISTRADO</th>
                    <th style="text-align:center;">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $usuario)
                <tr>
                    <td style="font-weight:600;color:#1e3a8a;">{{ $usuario->id }}</td>
                    <td><strong>{{ $usuario->name }}</strong></td>
                    <td style="color:#64748b;">{{ $usuario->username }}</td>
                    <td style="color:#64748b;">{{ $usuario->email }}</td>
                    <td>
                        @foreach($usuario->roles as $role)
                            <span class="role-badge {{ $role->name }}">{{ $role->name }}</span>
                        @endforeach
                    </td>
                    <td style="font-size:.8rem;color:#94a3b8;">
                        {{ $usuario->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td>
                        <div style="display:flex;gap:.4rem;justify-content:center;">
                            @can('usuarios.editar')
                            <a href="{{ route('usuarios.edit', $usuario->id) }}" class="tbl-btn green">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                            @endcan
                            
                            @can('usuarios.eliminar')
                            @if($usuario->id !== auth()->id())
                            <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST"
                                  onsubmit="return confirm('¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.');" 
                                  style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="tbl-btn red">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </form>
                            @else
                            <span class="tbl-btn" style="background:#e2e8f0;color:#94a3b8;cursor:not-allowed;">
                                <i class="bi bi-shield-lock"></i> Tu cuenta
                            </span>
                            @endif
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="bi bi-people"></i>
                            <p>No hay usuarios registrados en el sistema aún.</p>
                            @can('usuarios.crear')
                            <a href="{{ route('usuarios.create') }}" style="font-size:.78rem;color:#1e3a8a;font-weight:600;">
                                Crear primer usuario →
                            </a>
                            @endcan
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

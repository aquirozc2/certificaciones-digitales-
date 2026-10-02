@extends('layouts.app')
@php $activePage = 'memorandums'; @endphp

@section('title', 'Memorándums')

@section('content')

{{-- Breadcrumb --}}
<nav class="breadcrumb-custom mb-4">
    <span class="current">Memorándums</span>
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

<div class="page-card">
    <div class="page-card-head">
        <h2><i class="bi bi-file-earmark-text-fill" style="color:var(--blue-dark)"></i> Memorándums Registrados</h2>
        <a href="{{ route('memorandums.create') }}" class="btn-primary-custom" style="display:inline-flex;align-items:center;gap:.3rem;">
            <i class="bi bi-plus-circle"></i> Nuevo Memorándum
        </a>
    </div>
    <div class="page-card-body" style="padding:0;">
        @if($memorandums->isEmpty())
            <div style="text-align:center;padding:3rem 1rem;color:#94a3b8;">
                <i class="bi bi-inbox" style="font-size:2.5rem;display:block;margin-bottom:.5rem;"></i>
                <p style="font-size:.85rem;margin:0;">No hay memorándums registrados aún.</p>
                <a href="{{ route('memorandums.create') }}" style="font-size:.78rem;color:var(--blue-dark);font-weight:600;">Crear el primer memorándum →</a>
            </div>
        @else
            <div style="overflow-x:auto;">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Memorándum</th>
                            <th>Detalle</th>
                            <th>Resolución</th>
                            <th>Fecha de Resolución</th>
                            <th>Carrera</th>
                            <th>Departamento / Mención</th>
                            <th>Cátedra</th>
                            <th style="text-align:center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($memorandums as $memorandum)
                        <tr>
                            <td style="font-weight:600;color:var(--blue-dark);">{{ $memorandum->id }}</td>
                            <td><strong>{{ $memorandum->evento }}</strong></td>
                            <td style="color:#64748b;">{{ Str::limit($memorandum->detalle, 40) }}</td>
                            <td>{{ $memorandum->resolucion }}</td>
                            <td>{{ $memorandum->fecha_resolucion }}</td>
                            <td>{{ $memorandum->carrera }}</td>
                            <td>{{ $memorandum->departamento_mension }}</td>
                            <td>{{ $memorandum->catedra }}</td>
                            <td>
                                <div style="display:flex;gap:.35rem;justify-content:center;flex-wrap:wrap;">
                                    <a href="{{ route('memorandums.show', $memorandum->id) }}" class="btn-info-custom" style="padding:.3rem .6rem;font-size:.7rem;">
                                        <i class="bi bi-eye"></i> Ver
                                    </a>
                                    <a href="{{ route('memorandums.edit', $memorandum->id) }}" class="btn-success-custom" style="padding:.3rem .6rem;font-size:.7rem;text-decoration:none;">
                                        <i class="bi bi-pencil"></i> Editar
                                    </a>
                                    <form action="{{ route('memorandums.destroy', $memorandum->id) }}" method="POST"
                                          onsubmit="return confirm('¿Estás seguro de eliminar este memorándum?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger-custom" style="padding:.3rem .6rem;font-size:.7rem;">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@endsection
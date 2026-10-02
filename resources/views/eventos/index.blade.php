@extends('layouts.app')
@section('title', 'Certificados · Eventos')

@php $activePage = 'certificados'; @endphp

@section('content')
    {{-- Breadcrumb --}}
    <div class="breadcrumb-custom">
        <span class="current">Certificados</span>
    </div>

    <div class="page-card">
        <div class="page-card-head">
            <h2><i class="bi bi-award-fill" style="color:var(--blue-dark)"></i> Eventos Registrados</h2>
            <a href="{{ route('eventos.create') }}" class="btn-primary-custom" style="display:inline-flex; align-items:center; gap:.3rem;">
                <i class="bi bi-plus-circle"></i> Nuevo Evento
            </a>
        </div>
        <div class="page-card-body" style="padding:0;">
            @if(empty($eventos))
                <div style="text-align:center; padding:3rem 1rem; color:#94a3b8;">
                    <i class="bi bi-inbox" style="font-size:2.5rem; display:block; margin-bottom:.5rem;"></i>
                    <p style="font-size:.85rem; margin:0;">No hay eventos registrados aún.</p>
                    <a href="{{ route('eventos.create') }}" style="font-size:.78rem; color:var(--blue-dark); font-weight:600;">Crear el primer evento →</a>
                </div>
            @else
                <div style="overflow-x:auto;">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Evento</th>
                                <th>Detalle</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th style="text-align:center;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($eventos as $evento)
                            <tr>
                                <td style="font-weight:600; color:var(--blue-dark);">{{ $evento->id }}</td>
                                <td><strong>{{ $evento->evento }}</strong></td>
                                <td style="color:#64748b;">{{ Str::limit($evento->detalle, 50) }}</td>
                                <td>{{ $evento->fecha_inicio }}</td>
                                <td>{{ $evento->fecha_fin }}</td>
                                <td>
                                    <div style="display:flex; gap:.35rem; justify-content:center; flex-wrap:wrap;">
                                        <a href="{{ route('eventos.show', $evento->id) }}" class="btn-info-custom" style="padding:.3rem .6rem; font-size:.7rem;">
                                            <i class="bi bi-eye"></i> Ver
                                        </a>
                                        <a href="{{ route('eventos.edit', $evento->id) }}" class="btn-success-custom" style="padding:.3rem .6rem; font-size:.7rem; text-decoration:none;">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>
                                        <form action="{{ route('eventos.destroy', $evento->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este evento?');" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger-custom" style="padding:.3rem .6rem; font-size:.7rem;">
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
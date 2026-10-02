@extends('layouts.app')
@section('title', 'Registrar Nuevo Evento')

@php $activePage = 'certificados'; @endphp

@section('content')
    {{-- Breadcrumb --}}
    <div class="breadcrumb-custom">
        <a href="{{ route('eventos.index') }}">Certificados</a>
        <span class="sep"><i class="bi bi-chevron-right"></i></span>
        <span class="current">Nuevo Evento</span>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7 col-xl-6">
            <div class="page-card">
                <div class="page-card-head">
                    <h2><i class="bi bi-calendar-plus" style="color:var(--blue-dark)"></i> Registrar Nuevo Evento</h2>
                </div>
                <div class="page-card-body">
                    <form action="{{ route('eventos.store') }}" method="POST">
                        @csrf

                        <div style="margin-bottom:1rem;">
                            <label for="evento" class="form-label-custom">Nombre del Evento <span style="color:var(--red-accent);">*</span></label>
                            <input type="text" name="evento" id="evento" class="form-control-custom" placeholder="Ej: Curso de Anatomía 2026" required value="{{ old('evento') }}">
                            <div class="form-hint">Nombre descriptivo del evento o curso</div>
                            @error('evento')
                                <div style="color:var(--red-accent); font-size:.72rem; margin-top:.2rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div style="margin-bottom:1rem;">
                            <label for="detalle" class="form-label-custom">Detalle del Evento</label>
                            <textarea name="detalle" id="detalle" class="form-control-custom" rows="3" placeholder="Descripción adicional del evento (opcional)">{{ old('detalle') }}</textarea>
                            <div class="form-hint">Información adicional o descripción del evento</div>
                            @error('detalle')
                                <div style="color:var(--red-accent); font-size:.72rem; margin-top:.2rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row" style="margin-bottom:1rem;">
                            <div class="col-sm-6" style="margin-bottom:.8rem;">
                                <label for="fecha_inicio" class="form-label-custom">Fecha de Inicio <span style="color:var(--red-accent);">*</span></label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control-custom" required value="{{ old('fecha_inicio') }}">
                                @error('fecha_inicio')
                                    <div style="color:var(--red-accent); font-size:.72rem; margin-top:.2rem;">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="fecha_fin" class="form-label-custom">Fecha de Finalización <span style="color:var(--red-accent);">*</span></label>
                                <input type="date" name="fecha_fin" id="fecha_fin" class="form-control-custom" required value="{{ old('fecha_fin') }}">
                                @error('fecha_fin')
                                    <div style="color:var(--red-accent); font-size:.72rem; margin-top:.2rem;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div style="border-top:1px solid #f1f5f9; padding-top:1rem; display:flex; gap:.6rem; justify-content:flex-end;">
                            <a href="{{ route('eventos.index') }}" class="btn-secondary-custom" style="display:inline-flex; align-items:center; gap:.3rem;">
                                <i class="bi bi-x-lg"></i> Cancelar
                            </a>
                            <button type="submit" class="btn-primary-custom" style="display:inline-flex; align-items:center; gap:.3rem;">
                                <i class="bi bi-check-lg"></i> Registrar Evento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
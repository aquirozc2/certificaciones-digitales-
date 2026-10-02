@extends('layouts.app')
@php $activePage = 'memorandums'; @endphp
@section('title', 'Editar Memorándum')

@section('content')

{{-- Breadcrumb --}}
<nav class="breadcrumb-custom mb-4">
    <a href="{{ route('memorandums.index') }}"><i class="bi bi-house-door"></i> Memorándums</a>
    <span class="sep"><i class="bi bi-chevron-right"></i></span>
    <a href="{{ route('memorandums.show', $memorandum->id) }}">{{ Str::limit($memorandum->evento, 30) }}</a>
    <span class="sep"><i class="bi bi-chevron-right"></i></span>
    <span class="current">Editar</span>
</nav>

<div style="max-width:680px;">
    <div class="page-card">
        <div class="page-card-head">
            <h2><i class="bi bi-pencil-square" style="color:var(--blue-dark)"></i> Editar Memorándum</h2>
        </div>
        <div class="page-card-body">
            @if($errors->any())
            <div class="alert alert-danger mb-4" style="border-radius:8px;font-size:.82rem;">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('memorandums.update', $memorandum->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label-custom" for="evento">Nombre del Memorándum <span style="color:#dc2626;">*</span></label>
                    <input type="text" name="evento" id="evento" class="form-control-custom"
                           value="{{ old('evento', $memorandum->evento) }}" required>
                    <div class="form-hint">Ingrese el nombre del memorándum</div>
                </div>

                <div class="mb-3">
                    <label class="form-label-custom" for="detalle">Detalle</label>
                    <input type="text" name="detalle" id="detalle" class="form-control-custom"
                           value="{{ old('detalle', $memorandum->detalle) }}">
                    <div class="form-hint">Ingrese algún detalle</div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label-custom" for="resolucion">Resolución</label>
                        <input type="text" name="resolucion" id="resolucion" class="form-control-custom"
                               value="{{ old('resolucion', $memorandum->resolucion) }}">
                        <div class="form-hint">Ej: HCF-001/2026</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom" for="fecha_resolucion">Fecha de Resolución <span style="color:#dc2626;">*</span></label>
                        <input type="date" name="fecha_resolucion" id="fecha_resolucion" class="form-control-custom"
                               value="{{ old('fecha_resolucion', $memorandum->fecha_resolucion) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label-custom" for="carrera">Carrera</label>
                    <input type="text" name="carrera" id="carrera" class="form-control-custom"
                           value="{{ old('carrera', $memorandum->carrera) }}">
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label-custom" for="departamento_mension">Departamento / Mención</label>
                        <input type="text" name="departamento_mension" id="departamento_mension" class="form-control-custom"
                               value="{{ old('departamento_mension', $memorandum->departamento_mension) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom" for="catedra">Cátedra</label>
                        <input type="text" name="catedra" id="catedra" class="form-control-custom"
                               value="{{ old('catedra', $memorandum->catedra) }}">
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn-success-custom">
                        <i class="bi bi-floppy me-1"></i> Guardar Cambios
                    </button>
                    <a href="{{ route('memorandums.index') }}" class="btn-secondary-custom">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
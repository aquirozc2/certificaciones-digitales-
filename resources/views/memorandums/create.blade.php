@extends('layouts.app')
@php $activePage = 'memorandums'; @endphp
@section('title', 'Nuevo Memorándum')

@section('content')

{{-- Breadcrumb --}}
<nav class="breadcrumb-custom mb-4">
    <a href="{{ route('memorandums.index') }}"><i class="bi bi-house-door"></i> Memorándums</a>
    <span class="sep"><i class="bi bi-chevron-right"></i></span>
    <span class="current">Nuevo Memorándum</span>
</nav>

<div style="max-width:680px;">
    <div class="page-card">
        <div class="page-card-head">
            <h2><i class="bi bi-file-earmark-plus" style="color:var(--blue-dark)"></i> Registrar Nuevo Memorándum</h2>
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

            <form action="{{ route('memorandums.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label-custom" for="evento">Nombre del Memorándum <span style="color:#dc2626;">*</span></label>
                    <input type="text" name="evento" id="evento" class="form-control-custom" value="{{ old('evento') }}" required>
                    <div class="form-hint">Ingrese el nombre del memorándum</div>
                </div>

                <div class="mb-3">
                    <label class="form-label-custom" for="detalle">Detalle</label>
                    <input type="text" name="detalle" id="detalle" class="form-control-custom" value="{{ old('detalle') }}">
                    <div class="form-hint">Ingrese algún detalle</div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label-custom" for="resolucion">Resolución</label>
                        <input type="text" name="resolucion" id="resolucion" class="form-control-custom" value="{{ old('resolucion') }}">
                        <div class="form-hint">Ej: HCF-001/2026</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom" for="fecha_resolucion">Fecha de Resolución <span style="color:#dc2626;">*</span></label>
                        <input type="date" name="fecha_resolucion" id="fecha_resolucion" class="form-control-custom" value="{{ old('fecha_resolucion') }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label-custom" for="carrera">Carrera</label>
                    <input type="text" name="carrera" id="carrera" class="form-control-custom" value="{{ old('carrera') }}">
                    <div class="form-hint">Ingrese la carrera</div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label-custom" for="departamento_mension">Departamento / Mención</label>
                        <input type="text" name="departamento_mension" id="departamento_mension" class="form-control-custom" value="{{ old('departamento_mension') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom" for="catedra">Cátedra</label>
                        <input type="text" name="catedra" id="catedra" class="form-control-custom" value="{{ old('catedra') }}">
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn-primary-custom">
                        <i class="bi bi-floppy me-1"></i> Registrar
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
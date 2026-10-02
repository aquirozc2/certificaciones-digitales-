@extends('layouts.app')
@php $activePage = 'memorandums'; @endphp
@section('title', 'Registrar Persona')

@section('content')

{{-- Breadcrumb --}}
<nav class="breadcrumb-custom mb-4">
    <a href="{{ route('memorandums.index') }}"><i class="bi bi-file-earmark-text"></i> Memorándums</a>
    <span class="sep"><i class="bi bi-chevron-right"></i></span>
    <a href="{{ route('memorandums.show', $memorandum_id) }}">Personas</a>
    <span class="sep"><i class="bi bi-chevron-right"></i></span>
    <span class="current">Registrar Persona</span>
</nav>

<div style="max-width:860px;">
    <div class="page-card">
        <div class="page-card-head">
            <h2><i class="bi bi-person-plus" style="color:var(--blue-dark)"></i> Registrar Nueva Persona</h2>
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

            <form action="{{ route('personas.store') }}" method="POST">
                @csrf
                <input type="hidden" name="memorandum_id" value="{{ $memorandum_id }}">

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label-custom" for="tipo">Tipo <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="tipo" id="tipo" class="form-control-custom" value="{{ old('tipo') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom" for="carnet">Carnet</label>
                        <input type="text" name="carnet" id="carnet" class="form-control-custom" value="{{ old('carnet') }}">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-2">
                        <label class="form-label-custom" for="grado">Grado</label>
                        <input type="text" name="grado" id="grado" class="form-control-custom" value="{{ old('grado') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-custom" for="nombre">Nombre <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="nombre" id="nombre" class="form-control-custom" value="{{ old('nombre') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label-custom" for="paterno">Apellido Paterno</label>
                        <input type="text" name="paterno" id="paterno" class="form-control-custom" value="{{ old('paterno') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label-custom" for="materno">Apellido Materno</label>
                        <input type="text" name="materno" id="materno" class="form-control-custom" value="{{ old('materno') }}">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label-custom" for="correo">Correo Electrónico</label>
                        <input type="email" name="correo" id="correo" class="form-control-custom"
                               value="{{ old('correo') }}" placeholder="nombre@gmail.com">
                        <div class="form-hint">Ingrese su correo electrónico</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom" for="telefono">Teléfono Celular</label>
                        <input type="text" name="telefono" id="telefono" class="form-control-custom" value="{{ old('telefono') }}">
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn-primary-custom">
                        <i class="bi bi-person-plus me-1"></i> Registrar Persona
                    </button>
                    <a href="{{ route('memorandums.show', $memorandum_id) }}" class="btn-secondary-custom">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
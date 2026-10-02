@extends('layouts.app')
@php $activePage = 'memorandums'; @endphp
@section('title', 'Editar Persona')

@section('content')

{{-- Breadcrumb --}}
<nav class="breadcrumb-custom mb-4">
    <a href="{{ route('memorandums.index') }}"><i class="bi bi-file-earmark-text"></i> Memorándums</a>
    <span class="sep"><i class="bi bi-chevron-right"></i></span>
    <span class="current">Editar Persona</span>
</nav>

<div style="max-width:860px;">
    <div class="page-card">
        <div class="page-card-head">
            <h2><i class="bi bi-person-gear" style="color:var(--blue-dark)"></i> Editar Persona</h2>
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

            <form action="{{ route('personas.update', $persona->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label-custom" for="codigo">Código</label>
                        <input type="number" name="codigo" id="codigo" class="form-control-custom"
                               value="{{ old('codigo', $persona->codigo) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-custom" for="tipo">Tipo <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="tipo" id="tipo" class="form-control-custom"
                               value="{{ old('tipo', $persona->tipo) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-custom" for="carnet">Carnet</label>
                        <input type="text" name="carnet" id="carnet" class="form-control-custom"
                               value="{{ old('carnet', $persona->carnet) }}">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-2">
                        <label class="form-label-custom" for="grado">Grado</label>
                        <input type="text" name="grado" id="grado" class="form-control-custom"
                               value="{{ old('grado', $persona->grado) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-custom" for="nombre">Nombre <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="nombre" id="nombre" class="form-control-custom"
                               value="{{ old('nombre', $persona->nombre) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label-custom" for="paterno">Apellido Paterno</label>
                        <input type="text" name="paterno" id="paterno" class="form-control-custom"
                               value="{{ old('paterno', $persona->paterno) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label-custom" for="materno">Apellido Materno</label>
                        <input type="text" name="materno" id="materno" class="form-control-custom"
                               value="{{ old('materno', $persona->materno) }}">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label-custom" for="correo">Correo Electrónico</label>
                        <input type="email" name="correo" id="correo" class="form-control-custom"
                               value="{{ old('correo', $persona->correo) }}" placeholder="nombre@gmail.com">
                        <div class="form-hint">Ingrese su correo electrónico</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom" for="telefono">Teléfono Celular</label>
                        <input type="text" name="telefono" id="telefono" class="form-control-custom"
                               value="{{ old('telefono', $persona->telefono) }}">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label-custom" for="estado">Estado</label>
                        <select name="estado" id="estado" class="form-control-custom">
                            <option value="enviado"  {{ old('estado', $persona->estado) === 'enviado'  ? 'selected' : '' }}>Enviado</option>
                            <option value="anulado"  {{ old('estado', $persona->estado) === 'anulado'  ? 'selected' : '' }}>Anulado</option>
                            <option value="aprobado" {{ old('estado', $persona->estado) === 'aprobado' ? 'selected' : '' }}>Aprobado (apto para certificado)</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn-success-custom">
                        <i class="bi bi-floppy me-1"></i> Guardar Cambios
                    </button>
                    <a href="javascript:history.back()" class="btn-secondary-custom">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
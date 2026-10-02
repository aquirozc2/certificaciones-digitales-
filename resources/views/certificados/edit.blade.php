@extends('layouts.app')
@php $activePage = 'certificados'; @endphp

@section('title', 'Modificar Certificado')

@section('styles')
<style>
    .page-card {
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 8px 24px rgba(2, 6, 23, 0.08);
        overflow: hidden;
        background: #fff;
    }
    .page-card-head {
        padding: 1rem 1.2rem;
        border-bottom: 1px solid #e2e8f0;
        background: #fff;
    }
    .page-card-head h2 {
        margin: 0;
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: .45rem;
    }
    .page-card-body { padding: 1rem 1.2rem 1.2rem; }
    .form-label-custom {
        color: #334155;
        font-weight: 600;
        font-size: .86rem;
        margin-bottom: .35rem;
    }
    .form-control-custom {
        border: 1px solid #cbd5e1;
        border-radius: 9px;
        min-height: 42px;
        transition: border-color .15s, box-shadow .15s;
    }
    .form-control-custom:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.14);
        outline: none;
    }
    .form-hint {
        color: #94a3b8;
        font-size: .78rem;
        margin-top: .28rem;
    }
</style>
@endsection

@section('content')

{{-- Breadcrumb --}}
<nav class="breadcrumb-custom mb-4">
    <a href="{{ route('eventos.index') }}"><i class="bi bi-award"></i> Eventos</a>
    <span class="sep"><i class="bi bi-chevron-right"></i></span>
    <a href="{{ route('eventos.show', $certificado->evento_id) }}">Certificados</a>
    <span class="sep"><i class="bi bi-chevron-right"></i></span>
    <span>Editar #{{ $certificado->id }}</span>
</nav>

<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-9">
        <div class="page-card">
            <div class="page-card-head">
                <h2><i class="bi bi-pencil-square" style="color:#16a34a;"></i> Modificar Certificado</h2>
            </div>
            <div class="page-card-body">
                <form action="{{ route('certificados.update', $certificado->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="codigo" class="form-label-custom">Código <span style="color:#dc2626;">*</span></label>
                            <input type="number" name="codigo" id="codigo" class="form-control-custom" 
                                   value="{{ old('codigo', $certificado->codigo) }}" required>
                            @error('codigo')
                                <div style="color:#dc2626;font-size:.72rem;margin-top:.2rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="tipo" class="form-label-custom">Tipo <span style="color:#dc2626;">*</span></label>
                            <input type="text" name="tipo" id="tipo" class="form-control-custom" 
                                   placeholder="Ej: Participante, Expositor" value="{{ old('tipo', $certificado->tipo) }}" required>
                            @error('tipo')
                                <div style="color:#dc2626;font-size:.72rem;margin-top:.2rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="carnet" class="form-label-custom">Carnet</label>
                            <input type="text" name="carnet" id="carnet" class="form-control-custom" 
                                   placeholder="Número de carnet" value="{{ old('carnet', $certificado->carnet) }}">
                            @error('carnet')
                                <div style="color:#dc2626;font-size:.72rem;margin-top:.2rem;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-2">
                            <label for="grado" class="form-label-custom">Grado</label>
                            <input type="text" name="grado" id="grado" class="form-control-custom" 
                                   placeholder="Dr., Lic." value="{{ old('grado', $certificado->grado) }}">
                            @error('grado')
                                <div style="color:#dc2626;font-size:.72rem;margin-top:.2rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="nombre" class="form-label-custom">Nombre <span style="color:#dc2626;">*</span></label>
                            <input type="text" name="nombre" id="nombre" class="form-control-custom" 
                                   placeholder="Nombre(s)" value="{{ old('nombre', $certificado->nombre) }}" required>
                            @error('nombre')
                                <div style="color:#dc2626;font-size:.72rem;margin-top:.2rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="paterno" class="form-label-custom">Apellido Paterno</label>
                            <input type="text" name="paterno" id="paterno" class="form-control-custom" 
                                   placeholder="Paterno" value="{{ old('paterno', $certificado->paterno) }}">
                            @error('paterno')
                                <div style="color:#dc2626;font-size:.72rem;margin-top:.2rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="materno" class="form-label-custom">Apellido Materno</label>
                            <input type="text" name="materno" id="materno" class="form-control-custom" 
                                   placeholder="Materno" value="{{ old('materno', $certificado->materno) }}">
                            @error('materno')
                                <div style="color:#dc2626;font-size:.72rem;margin-top:.2rem;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="correo" class="form-label-custom">Correo Electrónico</label>
                            <input type="email" name="correo" id="correo" class="form-control-custom" 
                                   placeholder="ejemplo@correo.com" value="{{ old('correo', $certificado->correo) }}">
                            <div class="form-hint">Correo electrónico de contacto</div>
                            @error('correo')
                                <div style="color:#dc2626;font-size:.72rem;margin-top:.2rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="telefono" class="form-label-custom">Teléfono Celular</label>
                            <input type="text" name="telefono" id="telefono" class="form-control-custom" 
                                   placeholder="70000000" value="{{ old('telefono', $certificado->telefono) }}">
                            <div class="form-hint">Número de contacto</div>
                            @error('telefono')
                                <div style="color:#dc2626;font-size:.72rem;margin-top:.2rem;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div style="border-top:1px solid #f1f5f9;padding-top:1rem;margin-top:1.5rem;display:flex;gap:.6rem;justify-content:flex-end;">
                        <a href="javascript:history.back()" class="btn-secondary-custom" style="display:inline-flex;align-items:center;gap:.3rem;">
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
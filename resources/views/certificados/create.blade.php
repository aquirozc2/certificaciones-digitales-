@extends('layouts.app')
@php $activePage = 'certificados'; @endphp

@section('title', 'Registrar Certificado')

@section('content')

{{-- Breadcrumb --}}
<nav class="breadcrumb-custom mb-4">
    <a href="{{ route('eventos.index') }}"><i class="bi bi-award"></i> Eventos</a>
    <span class="sep"><i class="bi bi-chevron-right"></i></span>
    <a href="{{ route('eventos.show', $evento_id) }}">Certificados</a>
    <span class="sep"><i class="bi bi-chevron-right"></i></span>
    <span>Nuevo</span>
</nav>

<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-9">
        <div class="page-card">
            <div class="page-card-head">
                <h2><i class="bi bi-person-plus-fill" style="color:#1e3a8a;"></i> Registrar Nuevo Certificado</h2>
            </div>
            <div class="page-card-body">
                <form action="{{ route('certificados.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="evento_id" value="{{ $evento_id }}">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="tipo" class="form-label-custom">Tipo <span style="color:#dc2626;">*</span></label>
                            <input type="text" name="tipo" id="tipo" class="form-control-custom" 
                                   placeholder="Ej: Participante, Expositor" value="{{ old('tipo') }}" required>
                            @error('tipo')
                                <div style="color:#dc2626;font-size:.72rem;margin-top:.2rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="carnet" class="form-label-custom">Carnet</label>
                            <input type="text" name="carnet" id="carnet" class="form-control-custom" 
                                   placeholder="Número de carnet" value="{{ old('carnet') }}">
                            @error('carnet')
                                <div style="color:#dc2626;font-size:.72rem;margin-top:.2rem;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-2">
                            <label for="grado" class="form-label-custom">Grado</label>
                            <input type="text" name="grado" id="grado" class="form-control-custom" 
                                   placeholder="Dr., Lic." value="{{ old('grado') }}">
                            @error('grado')
                                <div style="color:#dc2626;font-size:.72rem;margin-top:.2rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="nombre" class="form-label-custom">Nombre <span style="color:#dc2626;">*</span></label>
                            <input type="text" name="nombre" id="nombre" class="form-control-custom" 
                                   placeholder="Nombre(s)" value="{{ old('nombre') }}" required>
                            @error('nombre')
                                <div style="color:#dc2626;font-size:.72rem;margin-top:.2rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="paterno" class="form-label-custom">Apellido Paterno</label>
                            <input type="text" name="paterno" id="paterno" class="form-control-custom" 
                                   placeholder="Paterno" value="{{ old('paterno') }}">
                            @error('paterno')
                                <div style="color:#dc2626;font-size:.72rem;margin-top:.2rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="materno" class="form-label-custom">Apellido Materno</label>
                            <input type="text" name="materno" id="materno" class="form-control-custom" 
                                   placeholder="Materno" value="{{ old('materno') }}">
                            @error('materno')
                                <div style="color:#dc2626;font-size:.72rem;margin-top:.2rem;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="correo" class="form-label-custom">Correo Electrónico</label>
                            <input type="email" name="correo" id="correo" class="form-control-custom" 
                                   placeholder="ejemplo@correo.com" value="{{ old('correo') }}">
                            <div class="form-hint">Correo electrónico de contacto</div>
                            @error('correo')
                                <div style="color:#dc2626;font-size:.72rem;margin-top:.2rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="telefono" class="form-label-custom">Teléfono Celular</label>
                            <input type="text" name="telefono" id="telefono" class="form-control-custom" 
                                   placeholder="70000000" value="{{ old('telefono') }}">
                            <div class="form-hint">Número de contacto</div>
                            @error('telefono')
                                <div style="color:#dc2626;font-size:.72rem;margin-top:.2rem;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div style="border-top:1px solid #f1f5f9;padding-top:1rem;margin-top:1.5rem;display:flex;gap:.6rem;justify-content:flex-end;">
                        <a href="{{ route('eventos.show', $evento_id) }}" class="btn-secondary-custom" style="display:inline-flex;align-items:center;gap:.3rem;">
                            <i class="bi bi-x-lg"></i> Cancelar
                        </a>
                        <button type="submit" class="btn-primary-custom" style="display:inline-flex;align-items:center;gap:.3rem;">
                            <i class="bi bi-check-lg"></i> Registrar Certificado
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
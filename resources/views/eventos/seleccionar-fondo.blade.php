@extends('layouts.app')
@php $activePage = 'certificados'; @endphp

@section('title', 'Seleccionar Fondo — ' . $evento->evento)

@section('styles')
<style>
    .event-banner {
        background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 100%);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        color: #fff;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(30,58,138,.25);
    }
    .event-banner h2 {
        font-size: 1.05rem;
        font-weight: 700;
        margin: 0 0 .2rem;
    }
    .event-banner p {
        font-size: .82rem;
        margin: 0;
        opacity: .85;
    }

    .galeria {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .fondo-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,.08);
        transition: all .2s;
        cursor: pointer;
        position: relative;
        border: 3px solid transparent;
    }
    .fondo-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,.15);
    }
    .fondo-card.selected {
        border-color: #16a34a;
        transform: scale(1.02);
    }

    .fondo-preview {
        width: 100%;
        height: 240px;
        object-fit: contain;
        background: #f8fafc;
        padding: .5rem;
    }

    .fondo-info {
        padding: .8rem 1rem;
        text-align: center;
    }
    .fondo-info h3 {
        color: #1e293b;
        margin: 0 0 .3rem;
        font-size: .9rem;
        font-weight: 600;
    }
    .fondo-info p {
        color: #64748b;
        font-size: .75rem;
        margin: 0 0 .5rem;
    }

    .ori-badge {
        display: inline-block;
        padding: .25rem .6rem;
        border-radius: 20px;
        font-size: .7rem;
        font-weight: 600;
    }
    .ori-badge.vertical {
        background: #dbeafe;
        color: #1e40af;
    }
    .ori-badge.horizontal {
        background: #fed7aa;
        color: #c2410c;
    }

    .checkmark {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #16a34a;
        color: #fff;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        box-shadow: 0 3px 10px rgba(22,163,74,.4);
        font-weight: 700;
    }
    .fondo-card.selected .checkmark {
        display: flex;
    }

    .action-bar {
        background: #fff;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        box-shadow: 0 2px 12px rgba(0,0,0,.07);
        display: flex;
        gap: .75rem;
        justify-content: flex-end;
        align-items: center;
    }
</style>
@endsection

@section('content')

{{-- Breadcrumb --}}
<nav class="breadcrumb-custom mb-4">
    <a href="{{ route('eventos.index') }}"><i class="bi bi-award"></i> Eventos</a>
    <span class="sep"><i class="bi bi-chevron-right"></i></span>
    <a href="{{ route('eventos.show', $evento->id) }}">Certificados</a>
    <span class="sep"><i class="bi bi-chevron-right"></i></span>
    <span>Seleccionar Fondo</span>
</nav>

{{-- Event banner --}}
<div class="event-banner">
    <h2><i class="bi bi-palette-fill"></i> Seleccionar Fondo para Certificado</h2>
    <p><strong>Evento:</strong> {{ $evento->evento }}</p>
</div>

<form action="{{ route('eventos.guardarFondo', $evento->id) }}" method="POST" id="formFondo">
    @csrf
    <input type="hidden" name="fondo_id" id="fondo_id" value="{{ $evento->fondo_id }}">

    {{-- Gallery of backgrounds --}}
    <div class="galeria">
        @foreach($fondos as $fondo)
        <div class="fondo-card" onclick="seleccionarFondo({{ $fondo->id }})" 
             id="fondo-{{ $fondo->id }}"
             @if($evento->fondo_id == $fondo->id) class="selected" @endif>
            <div class="checkmark"><i class="bi bi-check-lg"></i></div>
            <img src="{{ asset($fondo->ruta_fondo) }}" 
                 alt="{{ $fondo->nombre }}" 
                 class="fondo-preview">
            <div class="fondo-info">
                <h3>{{ $fondo->nombre }}</h3>
                <p>{{ $fondo->descripcion }}</p>
                <span class="ori-badge {{ $fondo->orientacion == 'portrait' ? 'vertical' : 'horizontal' }}">
                    {{ $fondo->orientacion == 'portrait' ? '📄 Vertical' : '📃 Horizontal' }}
                </span>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Action buttons --}}
    <div class="action-bar">
        <a href="{{ route('eventos.show', $evento->id) }}" class="btn-secondary-custom" style="display:inline-flex;align-items:center;gap:.3rem;">
            <i class="bi bi-x-lg"></i> Cancelar
        </a>
        <button type="submit" class="btn-primary-custom" id="btnContinuar" style="display:inline-flex;align-items:center;gap:.3rem;"
                @if(!$evento->fondo_id) disabled @endif>
            <i class="bi bi-arrow-right"></i> Continuar al Diseño
        </button>
    </div>
</form>

@endsection

@section('scripts')
<script>
    function seleccionarFondo(fondoId) {
        // Remover selección previa
        document.querySelectorAll('.fondo-card').forEach(card => {
            card.classList.remove('selected');
        });

        // Agregar selección actual
        document.getElementById('fondo-' + fondoId).classList.add('selected');
        document.getElementById('fondo_id').value = fondoId;
        document.getElementById('btnContinuar').disabled = false;
    }
</script>
@endsection

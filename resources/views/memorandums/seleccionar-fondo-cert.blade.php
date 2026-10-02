@extends('layouts.app')
@php $activePage = 'memorandums'; @endphp
@section('title', 'Seleccionar Fondo — ' . $memorandum->evento)

@section('styles')
<style>
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
        box-shadow: 0 2px 10px rgba(0,0,0,.08);
        border: 3px solid transparent;
        transition: transform .25s, box-shadow .25s, border-color .2s;
        cursor: pointer;
        position: relative;
    }
    .fondo-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,.14); }
    .fondo-card.selected { border-color: #16a34a; transform: translateY(-2px); }
    .fondo-preview { width: 100%; height: 220px; object-fit: contain; background: #f8fafc; padding: 6px; display: block; }
    .fondo-info { padding: 10px 12px; text-align: center; border-top: 1px solid #f1f5f9; }
    .fondo-info h3 { color: #1e293b; font-size: .88rem; font-weight: 700; margin: 0 0 3px; }
    .fondo-info p  { color: #64748b; font-size: .75rem; margin: 0 0 6px; }
    .ori-badge {
        display: inline-block; padding: 3px 10px; border-radius: 20px;
        font-size: .7rem; font-weight: 600;
    }
    .ori-badge.vertical   { background: #e0f2fe; color: #0369a1; }
    .ori-badge.horizontal { background: #fef3c7; color: #92400e; }
    .checkmark {
        position: absolute; top: 8px; right: 8px;
        background: #16a34a; color: #fff;
        width: 26px; height: 26px; border-radius: 50%;
        display: none; align-items: center; justify-content: center;
        font-size: .85rem; box-shadow: 0 2px 6px rgba(22,163,74,.4);
    }
    .fondo-card.selected .checkmark { display: flex; }
</style>
@endsection

@section('content')

{{-- Breadcrumb --}}
<nav class="breadcrumb-custom mb-4">
    <a href="{{ route('memorandums.index') }}"><i class="bi bi-file-earmark-text"></i> Memorándums</a>
    <span class="sep"><i class="bi bi-chevron-right"></i></span>
    <a href="{{ route('memorandums.show', $memorandum->id) }}">{{ Str::limit($memorandum->evento, 30) }}</a>
    <span class="sep"><i class="bi bi-chevron-right"></i></span>
    <span class="current">Seleccionar Fondo de Certificado</span>
</nav>

{{-- Info banner --}}
<div style="background:linear-gradient(135deg,#1e3a8a,#1d4ed8);border-radius:12px;padding:1.1rem 1.4rem;color:#fff;margin-bottom:1.5rem;display:flex;align-items:center;gap:1rem;box-shadow:0 4px 15px rgba(30,58,138,.25);">
    <div style="width:44px;height:44px;background:rgba(255,255,255,.15);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0;">
        <i class="bi bi-palette"></i>
    </div>
    <div>
        <div style="font-size:1rem;font-weight:700;margin-bottom:.15rem;">{{ $memorandum->evento }}</div>
        <div style="font-size:.82rem;opacity:.85;">{{ $memorandum->detalle }}</div>
        <div style="font-size:.75rem;opacity:.65;margin-top:.2rem;">Seleccione el fondo para el certificado del memorándum</div>
    </div>
</div>

<form action="{{ route('memorandums.guardarFondoCert', $memorandum->id) }}" method="POST" id="formFondo">
    @csrf
    <input type="hidden" name="fondo_id" id="fondo_id" value="{{ $memorandum->fondo_id }}">

    <div class="galeria">
        @foreach($fondos as $fondo)
        <div class="fondo-card {{ $memorandum->fondo_id == $fondo->id ? 'selected' : '' }}"
             id="fondo-{{ $fondo->id }}"
             onclick="seleccionarFondo({{ $fondo->id }})">
            <div class="checkmark"><i class="bi bi-check-lg"></i></div>
            <img src="{{ asset($fondo->ruta_fondo) }}" alt="{{ $fondo->nombre }}" class="fondo-preview">
            <div class="fondo-info">
                <h3>{{ $fondo->nombre }}</h3>
                <p>{{ $fondo->descripcion }}</p>
                <span class="ori-badge {{ $fondo->orientacion == 'portrait' ? 'vertical' : 'horizontal' }}">
                    {{ $fondo->orientacion == 'portrait' ? 'Vertical' : 'Horizontal' }}
                </span>
            </div>
        </div>
        @endforeach
    </div>

    <div class="d-flex gap-2 mt-2">
        <a href="{{ route('memorandums.show', $memorandum->id) }}" class="btn-secondary-custom">
            <i class="bi bi-arrow-left me-1"></i> Cancelar
        </a>
        <button type="submit" class="btn-primary-custom" id="btnContinuar"
                @if(!$memorandum->fondo_id) disabled style="opacity:.5;cursor:not-allowed;" @endif>
            Continuar al Diseño <i class="bi bi-arrow-right ms-1"></i>
        </button>
    </div>
</form>

@endsection

@section('scripts')
<script>
    function seleccionarFondo(fondoId) {
        document.querySelectorAll('.fondo-card').forEach(c => c.classList.remove('selected'));
        document.getElementById('fondo-' + fondoId).classList.add('selected');
        document.getElementById('fondo_id').value = fondoId;
        const btn = document.getElementById('btnContinuar');
        btn.disabled = false;
        btn.style.opacity = '1';
        btn.style.cursor = 'pointer';
    }
</script>
@endsection

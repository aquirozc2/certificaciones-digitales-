@php
    $nombreGenerador = $generadoPor ?? (auth()->user()?->name ?? 'Sistema');
@endphp
<div style="position: fixed; bottom: 14px; left: 0; right: 0; text-align: center; font-family: 'Roboto Slab', serif; font-size: 10px; color: #475569; z-index: 10;">
    <div style="display:inline-block; border-top:1px solid #cbd5e1; padding-top:6px; min-width:220px;">
        Generado por: <strong>{{ $nombreGenerador }}</strong>
    </div>
</div>

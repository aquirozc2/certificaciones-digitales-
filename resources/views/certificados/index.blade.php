@extends('layouts.app')
@php $activePage = 'certificados'; @endphp

@section('title', 'Certificados — ' . $evento->evento)

@section('styles')
<style>
    .event-info-card {
        background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 100%);
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        color: #fff;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(30,58,138,.25);
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .event-info-card .event-icon {
        width: 48px; height: 48px;
        background: rgba(255,255,255,.15);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }
    .event-info-card h2 { font-size: 1.15rem; font-weight: 700; margin: 0 0 .2rem; }
    .event-info-card p  { font-size: .85rem; margin: 0; opacity: .85; }

    .actions-bar {
        display: flex;
        gap: .75rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }
    .btn-action {
        display: inline-flex; align-items: center; gap: .45rem;
        padding: .6rem 1.15rem;
        border-radius: 10px;
        font-size: .85rem; font-weight: 600;
        border: none; cursor: pointer; text-decoration: none;
        transition: filter .15s, transform .15s, box-shadow .15s;
        white-space: nowrap;
        box-shadow: 0 3px 10px rgba(2,6,23,.12);
    }
    .btn-action:hover { filter: brightness(1.08); transform: translateY(-1px); }
    .btn-action.blue  { background: #1e3a8a; color: #fff; }
    .btn-action.amber { background: #d97706; color: #fff; }
    .btn-action.cyan  { background: #0891b2; color: #fff; }

    .cert-table-wrap {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(2,6,23,.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    .cert-table-wrap .table-responsive { overflow-x: auto; }
    table.cert-table { width: 100%; border-collapse: collapse; font-size: .82rem; }
    table.cert-table thead tr {
        background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 100%);
        color: #fff;
    }
    table.cert-table thead th {
        padding: .6rem .9rem; font-weight: 600;
        letter-spacing: .03em; white-space: nowrap; border: none;
    }
    table.cert-table tbody tr { border-bottom: 1px solid #f1f5f9; transition: background .12s; }
    table.cert-table tbody tr:hover { background: #f8faff; }
    table.cert-table tbody td {
        padding: .55rem .9rem; color: #374151;
        vertical-align: middle; white-space: nowrap;
    }
    .empty-state { text-align: center; padding: 3rem 1rem; color: #94a3b8; }
    .empty-state i { font-size: 2.5rem; display: block; margin-bottom: .75rem; }
    .empty-state p { font-size: .9rem; margin: 0; }

    .tbl-btn {
        display: inline-flex; align-items: center; gap: .3rem;
        padding: .3rem .68rem; border-radius: 8px;
        font-size: .78rem; font-weight: 600;
        border: none; cursor: pointer; text-decoration: none;
        transition: filter .15s, transform .15s; white-space: nowrap; line-height: 1.4;
    }
    .tbl-btn:hover { filter: brightness(1.08); transform: translateY(-1px); }
    .tbl-btn.green  { background: #16a34a; color: #fff; }
    .tbl-btn.red    { background: #dc2626; color: #fff; }
    .tbl-btn.yellow { background: #ca8a04; color: #fff; }

    .modal-content {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 24px 60px rgba(2, 6, 23, 0.28);
    }
    .modal-header  {
        background: #fff;
        color: #0f172a;
        border-radius: 12px 12px 0 0;
        border-bottom: 1px solid #e2e8f0;
    }
    .modal-header .btn-close { filter: none; }
    .modal-title { font-weight: 700; font-size: .98rem; }
</style>
@endsection

@section('content')

{{-- Breadcrumb --}}
<nav class="breadcrumb-custom mb-4">
    <a href="/"><i class="bi bi-house-door"></i></a>
    <span class="sep"><i class="bi bi-chevron-right"></i></span>
    <a href="{{ route('eventos.index') }}">Eventos</a>
    <span class="sep"><i class="bi bi-chevron-right"></i></span>
    <span>Certificados</span>
</nav>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-radius:10px;font-size:.875rem;">
    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
    @if(session('detalleErrores') && count(session('detalleErrores')) > 0)
        <hr>
        <strong>Detalles de errores:</strong>
        <ul class="mb-0 mt-2">
            @foreach(session('detalleErrores') as $err)
                <li><small>{{ $err }}</small></li>
            @endforeach
        </ul>
    @endif
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-radius:10px;font-size:.875rem;">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Event info banner --}}
<div class="event-info-card">
    <div class="event-icon"><i class="bi bi-calendar-event"></i></div>
    <div>
        <h2>{{ $evento->evento }}</h2>
        <p>{{ $evento->detalle }}</p>
        @if($evento->fecha_inicio ?? null)
        <p style="opacity:.7;font-size:.78rem;margin-top:.2rem;">
            <i class="bi bi-calendar3 me-1"></i>
            {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y') }}
            @if($evento->fecha_fin ?? null)
             — {{ \Carbon\Carbon::parse($evento->fecha_fin)->format('d/m/Y') }}
            @endif
        </p>
        @endif
    </div>
</div>

{{-- Action buttons --}}
<div class="actions-bar">
    <a class="btn-action blue" href="{{ route('certificados.create', $evento->id) }}">
        <i class="bi bi-file-earmark-plus"></i> Registrar Nuevo Certificado
    </a>
    @if($evento->fondo_id && $evento->tenor_html)
        <a class="btn-action amber" href="{{ route('eventos.disenar', $evento->id) }}">
            <i class="bi bi-pencil-square"></i> Editar Diseño de Certificado
        </a>
    @else
        <a class="btn-action amber" href="{{ route('eventos.seleccionarFondo', $evento->id) }}">
            <i class="bi bi-palette"></i> Diseñar Certificado
        </a>
    @endif
    <button class="btn-action cyan" data-bs-toggle="modal" data-bs-target="#modalGenerarMasivo">
        <i class="bi bi-files"></i> Generar Certificados Masivos
    </button>
</div>

{{-- Certificates table --}}
<div class="cert-table-wrap">
    <div class="table-responsive">
        <table class="cert-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>CÓDIGO</th>
                    <th>TIPO</th>
                    <th>GRADO</th>
                    <th>CARNET</th>
                    <th>NOMBRE</th>
                    <th>PATERNO</th>
                    <th>MATERNO</th>
                    <th>CORREO</th>
                    <th>TELÉFONO</th>
                    <th>MODIFICAR</th>
                    <th>ELIMINAR</th>
                    <th>CERTIFICADO</th>
                </tr>
            </thead>
            <tbody>
                @forelse($certificados as $certificado)
                <tr>
                    <td>{{ $certificado->id }}</td>
                    <td>{{ $certificado->codigo }}</td>
                    <td>{{ $certificado->tipo }}</td>
                    <td>{{ $certificado->grado }}</td>
                    <td>{{ $certificado->carnet }}</td>
                    <td>{{ $certificado->nombre }}</td>
                    <td>{{ $certificado->paterno }}</td>
                    <td>{{ $certificado->materno }}</td>
                    <td>{{ $certificado->correo }}</td>
                    <td>{{ $certificado->telefono }}</td>
                    <td>
                        <a class="tbl-btn green" href="{{ route('certificados.edit', $certificado->id) }}">
                            <i class="bi bi-pencil"></i> Modificar
                        </a>
                    </td>
                    <td>
                        <form action="{{ route('certificados.destroy', $certificado->id) }}" method="POST"
                              onsubmit="return confirm('¿Estás seguro de eliminar este certificado?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="tbl-btn red">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </form>
                    </td>
                    <td>
                        <a class="tbl-btn yellow" href="{{ route('certificados.certificado', $certificado->id) }}" target="_blank" rel="noopener noreferrer">
                            <i class="bi bi-file-earmark-pdf"></i> Generar
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="13">
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <p>No hay certificados registrados para este evento.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal: Generar Certificados Masivos --}}
<div class="modal fade" id="modalGenerarMasivo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-files me-2"></i>Generar Certificados Masivos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('certificados.generarMasivo') }}" method="POST" id="formMasivo">
                @csrf
                <input type="hidden" name="evento_id" value="{{ $evento->id }}">
                <div class="modal-body" style="padding:1.25rem;">
                    <div class="alert alert-info" style="font-size:.82rem;border-radius:8px;">
                        <i class="bi bi-info-circle me-1"></i>
                        <strong>Nota:</strong> La generación puede tardar aproximadamente 10 segundos por certificado.
                        Los certificados se guardarán en <code>storage/app/{{ $evento->id }}/</code>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom" for="id_desde">ID Desde</label>
                        <input type="number" class="form-control-custom" id="id_desde" name="id_desde" required min="1" placeholder="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom" for="id_hasta">ID Hasta</label>
                        <input type="number" class="form-control-custom" id="id_hasta" name="id_hasta" required min="1" placeholder="50">
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:.75rem 1.25rem;">
                    <button type="button" class="btn-secondary-custom" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-primary-custom" id="btnGenerar">
                        <i class="bi bi-play-fill me-1"></i> Generar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.getElementById('formMasivo').addEventListener('submit', function () {
        const btn = document.getElementById('btnGenerar');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Generando...';
    });
</script>
@endsection
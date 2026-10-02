@extends('layouts.app')
@php $activePage = 'memorandums'; @endphp

@section('title', 'Personas — ' . $memorandum->evento)

@section('styles')
<style>
    .memo-info-card {
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
    .memo-info-card .memo-icon {
        width: 48px; height: 48px;
        background: rgba(255,255,255,.15);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }
    .memo-info-card h2 { font-size: 1.15rem; font-weight: 700; margin: 0 0 .2rem; }
    .memo-info-card p  { font-size: .85rem; margin: 0; opacity: .85; }

    .actions-bar {
        display: flex;
        gap: .75rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }
    .btn-action {
        display: inline-flex; align-items: center; gap: .45rem;
        padding: .55rem 1.1rem;
        border-radius: 8px;
        font-size: .85rem; font-weight: 600;
        border: none; cursor: pointer; text-decoration: none;
        transition: filter .15s, transform .15s;
        white-space: nowrap;
    }
    .btn-action:hover { filter: brightness(1.1); transform: translateY(-1px); }
    .btn-action.blue   { background: #1e3a8a; color: #fff; }
    .btn-action.amber  { background: #d97706; color: #fff; }
    .btn-action.cyan   { background: #0891b2; color: #fff; }
    .btn-action.green  { background: #16a34a; color: #fff; }
    .btn-action.purple { background: #7c3aed; color: #fff; }

    .pers-table-wrap {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,.07);
        overflow: hidden;
    }
    .pers-table-wrap .table-responsive { overflow-x: auto; }
    table.pers-table { width: 100%; border-collapse: collapse; font-size: .82rem; }
    table.pers-table thead tr { background: #1e3a8a; color: #fff; }
    table.pers-table thead th {
        padding: .6rem .9rem; font-weight: 600;
        letter-spacing: .03em; white-space: nowrap; border: none;
    }
    table.pers-table tbody tr { border-bottom: 1px solid #f1f5f9; transition: background .12s; }
    table.pers-table tbody tr:hover { background: #f8faff; }
    table.pers-table tbody td {
        padding: .55rem .9rem; color: #374151;
        vertical-align: middle; white-space: nowrap;
    }
    .empty-state { text-align: center; padding: 3rem 1rem; color: #94a3b8; }
    .empty-state i { font-size: 2.5rem; display: block; margin-bottom: .75rem; }
    .empty-state p { font-size: .9rem; margin: 0; }

    .tbl-btn {
        display: inline-flex; align-items: center; gap: .3rem;
        padding: .28rem .65rem; border-radius: 6px;
        font-size: .78rem; font-weight: 600;
        border: none; cursor: pointer; text-decoration: none;
        transition: filter .15s; white-space: nowrap; line-height: 1.4;
    }
    .tbl-btn:hover { filter: brightness(1.1); }
    .tbl-btn.green  { background: #16a34a; color: #fff; }
    .tbl-btn.red    { background: #dc2626; color: #fff; }
    .tbl-btn.amber  { background: #d97706; color: #fff; }
    .tbl-btn.blue   { background: #1e3a8a; color: #fff; }
    .tbl-btn.cyan   { background: #0891b2; color: #fff; }
    .tbl-btn.muted  { background: #e2e8f0; color: #94a3b8; cursor: default; }

    .badge-estado {
        display: inline-block; padding: .2rem .55rem;
        border-radius: 999px; font-size: .72rem; font-weight: 700; letter-spacing: .03em;
    }
    .badge-enviado  { background: #e0f2fe; color: #0369a1; }
    .badge-anulado  { background: #fee2e2; color: #dc2626; }
    .badge-aprobado { background: #dcfce7; color: #16a34a; }

    .modal-content { border-radius: 12px; border: none; }
    .modal-header  { background: #1e3a8a; color: #fff; border-radius: 12px 12px 0 0; }
    .modal-header .btn-close { filter: invert(1); }
    .modal-title { font-weight: 700; font-size: .95rem; }
</style>
@endsection

@section('content')

{{-- Breadcrumb --}}
<nav class="breadcrumb-custom mb-4">
    <a href="{{ route('memorandums.index') }}"><i class="bi bi-file-earmark-text"></i> Memorándums</a>
    <span class="sep"><i class="bi bi-chevron-right"></i></span>
    <span>Personas</span>
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
@if(session('certGenerados') && count(session('certGenerados')) > 0)
<div class="card mb-4" style="border-radius:10px;border:1.5px solid #198754;">
    <div class="card-header" style="background:#d1e7dd;border-radius:9px 9px 0 0;padding:.6rem 1rem;">
        <strong style="color:#0f5132;">Certificados generados ({{ count(session('certGenerados')) }})</strong>
    </div>
    <div class="card-body p-0">
        <table class="table table-sm table-hover mb-0" style="font-size:.82rem;">
            <thead style="background:#f8f9fa;">
                <tr>
                    <th style="width:80px;padding:.4rem .8rem;">N° Cert.</th>
                    <th style="padding:.4rem .8rem;">Nombre completo</th>
                    <th style="padding:.4rem .8rem;">Tipo</th>
                </tr>
            </thead>
            <tbody>
                @foreach(session('certGenerados') as $cg)
                <tr>
                    <td style="padding:.35rem .8rem;"><strong>FAC.MED...N° {{ $cg['mcert_id'] }}/{{ date('Y') }}</strong></td>
                    <td style="padding:.35rem .8rem;">{{ $cg['nombre'] }}</td>
                    <td style="padding:.35rem .8rem;"><span class="badge bg-secondary">{{ $cg['tipo'] }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-radius:10px;font-size:.875rem;">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Memorandum info banner --}}
<div class="memo-info-card">
    <div class="memo-icon"><i class="bi bi-file-earmark-text"></i></div>
    <div>
        <h2>{{ $memorandum->evento }}</h2>
        <p>{{ $memorandum->detalle }}</p>
        @if($memorandum->fecha_resolucion)
        <p style="opacity:.7;font-size:.78rem;margin-top:.2rem;">
            <i class="bi bi-calendar3 me-1"></i>
            Resolución: {{ $memorandum->resolucion ?? '—' }} &nbsp;|&nbsp;
            {{ \Carbon\Carbon::parse($memorandum->fecha_resolucion)->format('d/m/Y') }}
        </p>
        @endif
    </div>
</div>

{{-- Action buttons --}}
<div class="actions-bar">
    @can('personas.crear')
    <a class="btn-action blue" href="{{ route('personas.create', $memorandum->id) }}">
        <i class="bi bi-person-plus"></i> Registrar Persona
    </a>
    @endcan
    @can('memorandums.editar')
    <a class="btn-action green" href="{{ route('memorandums.disenar', $memorandum->id) }}">
        <i class="bi bi-pencil-square"></i> Diseñar Memorándum
    </a>
    @endcan
    <button class="btn-action cyan" data-bs-toggle="modal" data-bs-target="#modalGenerarMasivo">
        <i class="bi bi-file-earmark-pdf"></i> Generar Memorándums
    </button>
    @can('memorandums.editar')
    <a class="btn-action amber" href="{{ route('memorandums.disenarCert', $memorandum->id) }}">
        <i class="bi bi-palette"></i> Diseñar Certificado
    </a>
    <button class="btn-action purple" data-bs-toggle="modal" data-bs-target="#modalGenerarMasivoCert">
        <i class="bi bi-award"></i> Generar Certificados
    </button>
    @endcan
</div>

{{-- Personas table --}}
<div class="pers-table-wrap">
    <div class="table-responsive">
        <table class="pers-table">
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
                    <th>ESTADO</th>
                    <th>MODIFICAR</th>
                    <th>ELIMINAR</th>
                    <th>MEMORÁNDUM</th>
                    <th>CERTIFICADO</th>
                </tr>
            </thead>
            <tbody>
                @forelse($personas as $persona)
                <tr>
                    <td style="font-weight:600;color:#1e3a8a;">{{ $persona->id }}</td>
                    <td>{{ $persona->codigo }}</td>
                    <td>{{ $persona->tipo }}</td>
                    <td>{{ $persona->grado }}</td>
                    <td>{{ $persona->carnet }}</td>
                    <td><strong>{{ $persona->nombre }}</strong></td>
                    <td>{{ $persona->paterno }}</td>
                    <td>{{ $persona->materno }}</td>
                    <td style="color:#64748b;">{{ $persona->correo }}</td>
                    <td>{{ $persona->telefono }}</td>
                    <td>
                        @can('personas.editar')
                        <div class="dropdown">
                            <button class="badge-estado badge-{{ $persona->estado }} border-0 dropdown-toggle estado-dropdown-btn"
                                    style="cursor:pointer;" data-bs-toggle="dropdown">
                                {{ ucfirst($persona->estado) }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" style="font-size:.8rem;min-width:160px;">
                                @foreach(['enviado','anulado','aprobado'] as $est)
                                @if($est !== $persona->estado)
                                <li>
                                    <form action="{{ route('personas.cambiarEstado', $persona->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="estado" value="{{ $est }}">
                                        <button type="submit" class="dropdown-item">
                                            @if($est === 'aprobado') ✅ @elseif($est === 'anulado') ❌ @else 📤 @endif
                                            {{ ucfirst($est) }}
                                        </button>
                                    </form>
                                </li>
                                @endif
                                @endforeach
                            </ul>
                        </div>
                        @else
                            <span class="badge-estado badge-{{ $persona->estado }}">{{ ucfirst($persona->estado) }}</span>
                        @endcan
                    </td>
                    <td>
                        @can('personas.editar')
                        <a class="tbl-btn green" href="{{ route('personas.edit', $persona->id) }}">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        @endcan
                    </td>
                    <td>
                        @can('personas.eliminar')
                        <form action="{{ route('personas.destroy', $persona->id) }}" method="POST"
                              onsubmit="return confirm('¿Estás seguro de eliminar esta persona?');" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="tbl-btn red">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </form>
                        @endcan
                    </td>
                    <td>
                        <a class="tbl-btn amber" href="{{ route('personas.certificado', $persona->id) }}" target="_blank">
                            <i class="bi bi-file-earmark-text"></i> Memo
                        </a>
                    </td>
                    <td>
                        @can('certificados.generar_pdf')
                            @if($memorandum->cert_tenor_html && $persona->estado === 'aprobado')
                                <a class="tbl-btn blue" href="{{ route('memorandums.certPersona', [$memorandum->id, $persona->id]) }}" target="_blank">
                                    <i class="bi bi-award"></i> Cert
                                </a>
                            @elseif($persona->estado === 'anulado')
                                <span class="tbl-btn muted" title="Persona anulada">Anulado</span>
                            @elseif(!$memorandum->cert_tenor_html)
                                <span class="tbl-btn muted">Sin diseño</span>
                            @else
                                <span class="tbl-btn muted" title="Cambia estado a Aprobado">Pendiente</span>
                            @endif
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="15">
                        <div class="empty-state">
                            <i class="bi bi-people"></i>
                            <p>No hay personas registradas en este memorándum aún.</p>
                            @can('personas.crear')
                            <a href="{{ route('personas.create', $memorandum->id) }}" style="font-size:.78rem;color:#1e3a8a;font-weight:600;">
                                Registrar primera persona →
                            </a>
                            @endcan
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal: Generar Memorándums Masivos --}}
<div class="modal fade" id="modalGenerarMasivo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-file-earmark-pdf me-2"></i>Generar Memorándums Masivos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form action="{{ route('memorandums.generarMasivo') }}" method="POST" id="formMasivo"
                  onsubmit="document.getElementById('btnGenerar').disabled=true; document.getElementById('btnGenerar').innerText='Generando...';">
                @csrf
                <input type="hidden" name="memorandum_id" value="{{ $memorandum->id }}">
                <div class="modal-body">
                    <p class="text-muted" style="font-size:.82rem;">
                        Se generarán los PDFs de las personas cuyo ID esté en el rango indicado y se guardarán en
                        <code>storage/app/memos/{{ $memorandum->id }}/</code>.
                    </p>
                    <div class="mb-3">
                        <label class="form-label-custom" for="id_desde">ID desde</label>
                        <input type="number" class="form-control-custom" id="id_desde" name="id_desde" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom" for="id_hasta">ID hasta</label>
                        <input type="number" class="form-control-custom" id="id_hasta" name="id_hasta" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary-custom" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-info-custom" id="btnGenerar">Generar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: Generar Certificados Masivos --}}
@can('memorandums.editar')
<div class="modal fade" id="modalGenerarMasivoCert" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-award me-2"></i>Generar Certificados Masivos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            @if($memorandum->cert_tenor_html)
            <form action="{{ route('memorandums.generarMasivoCert') }}" method="POST" id="formMasivoCert"
                  onsubmit="document.getElementById('btnGenerarCert').disabled=true; document.getElementById('btnGenerarCert').innerText='Generando...';">
                @csrf
                <input type="hidden" name="memorandum_id" value="{{ $memorandum->id }}">
                <div class="modal-body">
                    @php $aprobados = $personas->where('estado','aprobado')->count(); @endphp
                    <p class="text-muted" style="font-size:.82rem;">
                        Se generarán certificados para todas las personas con estado
                        <strong class="badge-estado badge-aprobado">Aprobado</strong>
                        de este memorándum.
                    </p>
                    <p style="font-size:.85rem;margin-bottom:0;">
                        Personas aprobadas: <strong>{{ $aprobados }}</strong>
                    </p>
                    @if($aprobados === 0)
                    <div class="alert alert-warning mt-3 mb-0" style="border-radius:8px;font-size:.82rem;">
                        No hay personas con estado <strong>Aprobado</strong>. Cambia el estado desde la tabla antes de generar.
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary-custom" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-primary-custom" id="btnGenerarCert" {{ $aprobados === 0 ? 'disabled' : '' }}>
                        Generar {{ $aprobados }} Certificado(s)
                    </button>
                </div>
            </form>
            @else
            <div class="modal-body">
                <div class="alert alert-warning mb-0" style="border-radius:8px;font-size:.85rem;">
                    Este memorándum aún no tiene un diseño de certificado configurado.<br>
                    <a href="{{ route('memorandums.disenarCert', $memorandum->id) }}"
                       class="btn-action amber d-inline-flex mt-2" style="font-size:.8rem;">
                        <i class="bi bi-palette"></i> Diseñar Certificado
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary-custom" data-bs-dismiss="modal">Cerrar</button>
            </div>
            @endif
        </div>
    </div>
</div>
@endcan

{{-- Sección: Certificados generados de este memorándum --}}
@if(isset($mcertificados) && $mcertificados->count() > 0)
<div class="pers-table-wrap mt-4">
    {{-- Header colapsable --}}
    <div style="background:#1e3a8a;border-radius:12px 12px 0 0;padding:.6rem 1rem;
                display:flex;align-items:center;justify-content:space-between;cursor:pointer;"
         onclick="toggleCerts(this)">
        <span style="color:#fff;font-weight:700;font-size:.9rem;">
            Certificados generados de este memorándum
            <span class="badge ms-2" style="background:rgba(255,255,255,.25);font-size:.75rem;">
                {{ $mcertificados->count() }}
            </span>
        </span>
        <span id="certChevron" style="color:#fff;font-size:1rem;transition:transform .2s;">▲</span>
    </div>
    <div id="tablaCerts" class="table-responsive">
        <table class="pers-table">
            <thead>
                <tr>
                    <th>ID Certificado</th>
                    <th>ID Persona</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mcertificados as $mc)
                <tr>
                    <td><strong style="color:#1e3a8a;">{{ $mc->id }}/{{ date('Y') }}</strong></td>
                    <td>
                        <span class="badge" style="background:#e0f2fe;color:#0369a1;font-size:.75rem;font-weight:700;">
                            {{ $mc->persona_id }}
                        </span>
                    </td>
                    <td>{{ trim($mc->persona->grado . ' ' . $mc->persona->nombre . ' ' . $mc->persona->paterno . ' ' . $mc->persona->materno) }}</td>
                    <td><span class="badge-estado badge-aprobado">{{ $mc->persona->tipo }}</span></td>
                    <td>
                        @can('memorandums.editar')
                        <a href="{{ route('memorandums.certPersona', [$memorandum->id, $mc->persona_id]) }}"
                           target="_blank" class="tbl-btn blue">
                            <i class="bi bi-file-earmark-pdf"></i> Ver PDF
                        </a>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<script>
function toggleCerts(header) {
    var tabla   = document.getElementById('tablaCerts');
    var chevron = document.getElementById('certChevron');
    if (tabla.style.display === 'none') {
        tabla.style.display = '';
        chevron.style.transform = 'rotate(0deg)';
    } else {
        tabla.style.display = 'none';
        chevron.style.transform = 'rotate(180deg)';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.estado-dropdown-btn').forEach(function (btn) {
        new bootstrap.Dropdown(btn, {
            popperConfig: function(defaultConfig) {
                return Object.assign({}, defaultConfig, { strategy: 'fixed' });
            }
        });
    });
});
</script>
@endsection

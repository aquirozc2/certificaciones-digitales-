@extends('layouts.app')

@section('title', 'Resultados de búsqueda')

@section('content')
<div class="container-fluid py-4">
    <h4 class="mb-4"><i class="bi bi-search"></i> Resultados para "<strong>{{ e($q) }}</strong>"</h4>

    {{-- ── EVENTOS ── --}}
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center gap-2">
            <i class="bi bi-calendar-event"></i>
            <strong>Eventos</strong>
            <span class="badge bg-primary rounded-pill">{{ $eventos->count() }}</span>
        </div>
        @if($eventos->isEmpty())
            <div class="card-body text-muted">Sin resultados.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Evento</th>
                            <th>Detalle</th>
                            <th>Fecha inicio</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($eventos as $evento)
                        <tr>
                            <td>{{ $evento->id }}</td>
                            <td>{{ $evento->evento }}</td>
                            <td>{{ Str::limit($evento->detalle, 60) }}</td>
                            <td>{{ $evento->fecha_inicio }}</td>
                            <td><a href="{{ route('eventos.show', $evento->id) }}" class="btn btn-sm btn-outline-primary">Ver</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- ── CERTIFICADOS ── --}}
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center gap-2">
            <i class="bi bi-award"></i>
            <strong>Certificados</strong>
            <span class="badge bg-success rounded-pill">{{ $certificados->count() }}</span>
        </div>
        @if($certificados->isEmpty())
            <div class="card-body text-muted">Sin resultados.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Código</th>
                            <th>Nombre completo</th>
                            <th>Carnet</th>
                            <th>Evento</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($certificados as $cert)
                        <tr>
                            <td>{{ $cert->id }}</td>
                            <td>{{ $cert->codigo }}</td>
                            <td>{{ $cert->nombre }} {{ $cert->paterno }} {{ $cert->materno }}</td>
                            <td>{{ $cert->carnet }}</td>
                            <td>{{ $cert->evento->evento ?? '—' }}</td>
                            <td><a href="{{ route('eventos.show', $cert->evento_id) }}" class="btn btn-sm btn-outline-success">Ver evento</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- ── MEMORÁNDUMS ── --}}
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center gap-2">
            <i class="bi bi-file-earmark-text"></i>
            <strong>Memorándums</strong>
            <span class="badge bg-warning text-dark rounded-pill">{{ $memorandums->count() }}</span>
        </div>
        @if($memorandums->isEmpty())
            <div class="card-body text-muted">Sin resultados.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Evento</th>
                            <th>Resolución</th>
                            <th>Carrera</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($memorandums as $memo)
                        <tr>
                            <td>{{ $memo->id }}</td>
                            <td>{{ $memo->evento }}</td>
                            <td>{{ $memo->resolucion }}</td>
                            <td>{{ $memo->carrera }}</td>
                            <td><a href="{{ route('memorandums.show', $memo->id) }}" class="btn btn-sm btn-outline-warning">Ver</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- ── PERSONAS ── --}}
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center gap-2">
            <i class="bi bi-people"></i>
            <strong>Personas</strong>
            <span class="badge bg-info rounded-pill">{{ $personas->count() }}</span>
        </div>
        @if($personas->isEmpty())
            <div class="card-body text-muted">Sin resultados.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre completo</th>
                            <th>Carnet</th>
                            <th>Estado</th>
                            <th>Memorándum</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($personas as $persona)
                        <tr>
                            <td>{{ $persona->id }}</td>
                            <td>{{ $persona->nombre }} {{ $persona->paterno }} {{ $persona->materno }}</td>
                            <td>{{ $persona->carnet }}</td>
                            <td>
                                <span class="badge bg-{{ $persona->estado === 'aprobado' ? 'success' : ($persona->estado === 'enviado' ? 'primary' : 'danger') }}">
                                    {{ ucfirst($persona->estado) }}
                                </span>
                            </td>
                            <td>{{ $persona->memorandum_id }} - {{ $persona->memorandum->evento ?? '—' }}</td>
                            <td><a href="{{ route('memorandums.show', $persona->memorandum_id) }}" class="btn btn-sm btn-outline-info">Ver memo</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

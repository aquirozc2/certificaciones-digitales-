@extends('layouts.app')
@section('title', 'Reportes · Sistema de Certificaciones')

@php $activePage = 'reportes'; @endphp

@section('content')
    {{-- Breadcrumb --}}
    <div class="breadcrumb-custom">
        <span class="current">Reportes</span>
    </div>

    {{-- Mensaje de éxito --}}
    @if (session('success'))
        <div style="background:#dcfce7; border:1px solid #86efac; border-radius:8px; padding:0.85rem 1rem; margin-bottom:1.5rem; color:#166534; font-size:.8rem; display:flex; align-items:center; gap:.5rem;">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Mensaje de error --}}
    @if (session('error'))
        <div style="background:#fee2e2; border:1px solid #fca5a5; border-radius:8px; padding:0.85rem 1rem; margin-bottom:1.5rem; color:#991b1b; font-size:.8rem; display:flex; align-items:center; gap:.5rem;">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="page-card">
        <div class="page-card-head">
            <h2><i class="bi bi-file-earmark-bar-graph" style="color:var(--blue-dark)"></i> Generación de Reportes del Sistema</h2>
        </div>
        <div class="page-card-body">
            <p style="color:#64748b; font-size:.85rem; margin-bottom:1.3rem;">Seleccione el tipo de reporte y los parámetros para exportar la información.</p>

            {{-- Formulario de generación de reportes --}}
            <form action="{{ route('reportes.store') }}" method="POST" id="formReporte">
                @csrf
                
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.2rem; margin-bottom:1.5rem;">
                    {{-- Tipo de Reporte --}}
                    <div>
                        <label class="form-label-custom">Tipo de Reporte</label>
                        <select name="tipo_reporte" class="form-control-custom" id="tipoReporte" required>
                            <option value="">-- Seleccionar --</option>
                            @foreach ($tiposDisponibles as $valor => $etiqueta)
                                <option value="{{ $valor }}">{{ $etiqueta }}</option>
                            @endforeach
                        </select>
                        @error('tipo_reporte')
                            <div style="color:var(--red-accent); font-size:.7rem; margin-top:.3rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Criterio de Búsqueda (dinámico según tipo) --}}
                    <div>
                        <label class="form-label-custom">Criterio de Búsqueda</label>
                        <select name="criterios[filtro]" class="form-control-custom" id="criterioFiltro" style="display:none;">
                            <option value="">-- Opcional --</option>
                        </select>
                        <div style="display:none; font-size:.7rem; color:#94a3b8; margin-top:.3rem;" id="criterioHelp"></div>
                    </div>
                </div>

                {{-- Rango de fechas --}}
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.2rem; margin-bottom:1.5rem;">
                    <div>
                        <label class="form-label-custom">Desde</label>
                        <input type="date" name="fecha_desde" class="form-control-custom" id="fechaDesde">
                        @error('fecha_desde')
                            <div style="color:var(--red-accent); font-size:.7rem; margin-top:.3rem;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label-custom">Hasta</label>
                        <input type="date" name="fecha_hasta" class="form-control-custom" id="fechaHasta">
                        @error('fecha_hasta')
                            <div style="color:var(--red-accent); font-size:.7rem; margin-top:.3rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Formato de salida --}}
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.2rem; margin-bottom:1.5rem;">
                    <div>
                        <label class="form-label-custom">Formato de Salida</label>
                        <div style="display:flex; gap:.6rem;">
                            <label style="display:flex; align-items:center; gap:.4rem; font-size:.8rem; cursor:pointer;">
                                <input type="radio" name="formato" value="pdf" checked>
                                <i class="bi bi-file-pdf-fill" style="color:#dc2626;"></i> PDF
                            </label>
                            <label style="display:flex; align-items:center; gap:.4rem; font-size:.8rem; cursor:pointer;">
                                <input type="radio" name="formato" value="excel">
                                <i class="bi bi-file-earmark-spreadsheet-fill" style="color:#16a34a;"></i> Excel
                            </label>
                            <label style="display:flex; align-items:center; gap:.4rem; font-size:.8rem; cursor:pointer;">
                                <input type="radio" name="formato" value="csv">
                                <i class="bi bi-file-text-fill" style="color:#0284c7;"></i> CSV
                            </label>
                        </div>
                        @error('formato')
                            <div style="color:var(--red-accent); font-size:.7rem; margin-top:.3rem;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div></div>
                </div>

                {{-- Botones de acción --}}
                <div style="display:flex; gap:.6rem; justify-content:flex-end;">
                    <button type="reset" class="btn-secondary-custom">
                        <i class="bi bi-arrow-clockwise"></i> Limpiar Filtros
                    </button>
                    <button type="submit" class="btn-primary-custom">
                        <i class="bi bi-download"></i> Generar Reporte
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Historial de Reportes --}}
    <div class="page-card" style="margin-top:1.8rem;">
        <div class="page-card-head">
            <h2><i class="bi bi-clock-history" style="color:var(--blue-dark)"></i> Historial de Reportes Generados</h2>
        </div>
        <div class="page-card-body" style="padding:0;">
            @if($reportes->isEmpty())
                <div style="text-align:center; padding:3rem 1rem; color:#94a3b8;">
                    <i class="bi bi-inbox" style="font-size:2.5rem; display:block; margin-bottom:.5rem;"></i>
                    <p style="font-size:.85rem; margin:0;">No hay reportes generados aún.</p>
                </div>
            @else
                <div style="overflow-x:auto;">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>ID REPORTE</th>
                                <th>TIPO DE REPORTE</th>
                                <th>CRITERIOS DE SELECCIÓN</th>
                                <th>FECHA GENERADO</th>
                                <th style="text-align:center;">ESTADO</th>
                                <th>FORMATO</th>
                                <th style="text-align:center;">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reportes as $reporte)
                                <tr>
                                    <td style="font-weight:600; color:var(--blue-dark);">#{{ $reporte->id }}</td>
                                    <td>
                                        <strong>{{ $reporte->getTipoReporteDescripcion() }}</strong>
                                        <br>
                                        <span style="font-size:.7rem; color:#64748b;">
                                            @if ($reporte->fecha_desde || $reporte->fecha_hasta)
                                                {{ $reporte->fecha_desde?->format('d/m/Y') ?? '--' }} a {{ $reporte->fecha_hasta?->format('d/m/Y') ?? '--' }}
                                            @else
                                                Sin rango de fechas
                                            @endif
                                        </span>
                                    </td>
                                    <td style="font-size:.75rem; color:#64748b;">
                                        @if ($reporte->criterios && count($reporte->criterios) > 0)
                                            @foreach ($reporte->criterios as $clave => $valor)
                                                <div>{{ ucfirst(str_replace('_', ' ', $clave)) }}: {{ $valor }}</div>
                                            @endforeach
                                        @else
                                            <em>Sin criterios adicionales</em>
                                        @endif
                                    </td>
                                    <td style="font-size:.78rem;">
                                        {{ $reporte->created_at->format('d/m/Y H:i') }}
                                        <br>
                                        <span style="color:#94a3b8; font-size:.7rem;">Por {{ $reporte->user->name ?? 'N/A' }}</span>
                                    </td>
                                    <td style="text-align:center;">
                                        <span style="display:inline-block; padding:.25rem .6rem; border-radius:4px; font-size:.7rem; font-weight:600; background:{{ str_replace('#', '', $reporte->getColorEstado()) }}22; color:{{ $reporte->getColorEstado() }};">
                                            {{ $reporte->getEstadoDescripcion() }}
                                        </span>
                                        @if ($reporte->estado === 'error' && $reporte->mensaje_error)
                                            <div style="font-size:.65rem; color:var(--red-accent); margin-top:.2rem;" title="{{ $reporte->mensaje_error }}">
                                                ⚠️ {{ Str::limit($reporte->mensaje_error, 30) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td style="text-align:center;">
                                        <div style="display:flex; align-items:center; justify-content:center; gap:.3rem;">
                                            <i class="bi {{ $reporte->getIconoFormato() }}" style="font-size:1rem; color:{{ match($reporte->formato) { 'pdf' => '#dc2626', 'excel' => '#16a34a', 'csv' => '#0284c7', default => '#6b7280' } }};"></i>
                                            <span style="font-size:.75rem;">{{ strtoupper($reporte->formato) }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="display:flex; gap:.35rem; justify-content:center; flex-wrap:wrap;">
                                            @if ($reporte->estado === 'generado' && $reporte->ruta_archivo)
                                                <a href="{{ route('reportes.download', $reporte->id) }}" class="btn-info-custom" style="padding:.3rem .6rem; font-size:.7rem; text-decoration:none;">
                                                    <i class="bi bi-download"></i> Descargar
                                                </a>
                                            @else
                                                <button type="button" class="btn-secondary-custom" style="padding:.3rem .6rem; font-size:.7rem; cursor:not-allowed; opacity:0.5;" disabled>
                                                    <i class="bi bi-download"></i> Descargar
                                                </button>
                                            @endif
                                            
                                            <form action="{{ route('reportes.destroy', $reporte->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-danger-custom" style="padding:.3rem .6rem; font-size:.7rem;" onclick="return confirm('¿Eliminar este reporte?')">
                                                    <i class="bi bi-trash"></i> Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                <div style="padding:1rem; border-top:1px solid #f1f5f9; display:flex; justify-content:center;">
                    {{ $reportes->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>

    <script>
        // Script para mostrar criterios dinámicos según el tipo de reporte
        const tipoReporteSelect = document.getElementById('tipoReporte');
        const criterioFiltro = document.getElementById('criterioFiltro');
        const criterioHelp = document.getElementById('criterioHelp');

        // Datos dinámicos desde el servidor
        const eventosData = @json($eventos->map(fn($e) => ['value' => $e->id, 'text' => $e->evento]));
        const departamentosData = @json($departamentos->map(fn($d) => ['value' => $d, 'text' => $d ?? 'Sin departamento']));

        const criteriosPorTipo = {
            'certificados_emitidos': {
                label: 'Evento',
                options: eventosData,
                help: 'Filtra certificados por un evento específico (opcional)'
            },
            'uso_sistema': {
                label: 'Usuario',
                options: [],
                help: 'Se agregan automáticamente todos los usuarios del sistema'
            },
            'memorandums_departamento': {
                label: 'Departamento',
                options: departamentosData,
                help: 'Filtra memorándums por departamento (opcional)'
            },
            'certificados_por_evento': {
                label: 'Sin Criterios',
                options: [],
                help: 'Este reporte no requiere criterios adicionales'
            },
            'actividad_usuarios': {
                label: 'Sin Criterios',
                options: [],
                help: 'Este reporte agrupa todas las acciones del sistema'
            },
            'auditoria_sistema': {
                label: 'Sin Criterios',
                options: [],
                help: 'Muestra el registro de auditoría completo del sistema'
            }
        };

        tipoReporteSelect.addEventListener('change', function() {
            const tipo = this.value;
            
            if (tipo && criteriosPorTipo[tipo]) {
                const criterios = criteriosPorTipo[tipo];
                
                // Actualizar etiqueta
                criterioFiltro.previousElementSibling.textContent = criterios.label;
                
                // Limpiar opciones
                criterioFiltro.innerHTML = '<option value="">-- Opcional --</option>';
                
                // Agregar opciones
                criterios.options.forEach(opt => {
                    const option = document.createElement('option');
                    option.value = opt.value;
                    option.textContent = opt.text;
                    criterioFiltro.appendChild(option);
                });
                
                // Mostrar/ocultar criterios
                if (criterios.options.length > 0) {
                    criterioFiltro.style.display = 'block';
                    criterioFiltro.name = 'criterios[filtro]';
                } else {
                    criterioFiltro.style.display = 'none';
                    criterioFiltro.name = '';
                }
                
                // Mostrar ayuda
                criterioHelp.textContent = criterios.help;
                criterioHelp.style.display = 'block';
            }
        });

        // Validar fechas
        document.getElementById('fechaHasta').addEventListener('change', function() {
            const desde = document.getElementById('fechaDesde').value;
            const hasta = this.value;
            
            if (desde && hasta && desde > hasta) {
                alert('La fecha "Hasta" no puede ser anterior a la fecha "Desde"');
                this.value = '';
            }
        });
    </script>
@endsection

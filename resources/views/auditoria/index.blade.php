@extends('layouts.app')

@section('title', 'Auditoría del Sistema')

@php
    $activePage = 'auditoria';
@endphp

@section('content')
<style>
    /* Estilos específicos para auditoría */
    .filters-card { background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,.06); margin-bottom: 1.5rem; }
    .filters-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem; }
    .audit-table { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.06); margin-bottom: 0; }
    .audit-table table { width: 100%; border-collapse: collapse; margin-bottom: 0; }
    .audit-table thead { background: linear-gradient(135deg, #1e3a8a 0%, #172554 100%); color: white; }
    .audit-table thead th { padding: 1rem; text-align: left; font-weight: 600; font-size: .85rem; text-transform: uppercase; letter-spacing: .025em; }
    .audit-table tbody tr { border-bottom: 1px solid #f1f5f9; transition: background-color 0.2s; }
    .audit-table tbody tr:hover { background-color: #f8fafc; }
    .audit-table tbody td { padding: 1rem; font-size: .875rem; color: #334155; }

    .badge-accion { display: inline-block; padding: .25rem .75rem; border-radius: 6px; font-size: .75rem; font-weight: 600; text-transform: uppercase; letter-spacing: .015em; }
    .badge-crear { background: #dbeafe; color: #1e40af; }
    .badge-actualizar { background: #fef3c7; color: #92400e; }
    .badge-eliminar { background: #fee2e2; color: #991b1b; }
    .badge-login { background: #d1fae5; color: #065f46; }
    .badge-logout { background: #e0e7ff; color: #3730a3; }
    .badge-default { background: #f1f5f9; color: #475569; }

    .entity-badge { display: inline-block; padding: .2rem .6rem; background: #f1f5f9; border-radius: 4px; font-size: .75rem; color: #475569; font-family: 'Courier New', monospace; }
    .user-info { display: flex; align-items: center; gap: .5rem; }
    .user-avatar { width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: .75rem; }
    .user-name { font-weight: 500; color: #1e293b; }
    .timestamp { color: #64748b; font-size: .8rem; }
    .btn-filter { background: linear-gradient(135deg, #1e3a8a 0%, #172554 100%); color: white; border: none; padding: .5rem 1.5rem; border-radius: 8px; font-weight: 600; font-size: .875rem; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; }
    .btn-reset { background: white; color: #64748b; border: 1px solid #e2e8f0; padding: .5rem 1.5rem; border-radius: 8px; font-weight: 600; font-size: .875rem; cursor: pointer; transition: all 0.2s; }
    .no-results { text-align: center; padding: 3rem; color: #64748b; }
    .details-toggle { background: none; border: none; color: #3b82f6; cursor: pointer; font-size: .85rem; text-decoration: underline; padding: 0; }

    /* Paginación (fix de íconos enormes) */
    .pagination { display: flex; justify-content: center; align-items: center; gap: .4rem; margin-top: .75rem; flex-wrap: wrap; }
    .pagination a,
    .pagination span {
        min-width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-size: .875rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        padding: 0 .75rem;
    }
    .pagination a {
        background: white;
        color: #64748b;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 2px rgba(0,0,0,.02);
    }
    .pagination a:hover {
        background: #f8fafc;
        border-color: #1e3a8a;
        color: #1e3a8a;
        transform: translateY(-1px);
    }
    .pagination .active span {
        background: linear-gradient(135deg, #1e3a8a 0%, #172554 100%);
        color: white;
        border: none;
        box-shadow: 0 4px 8px rgba(30, 58, 138, 0.25);
    }
    .pagination .disabled span {
        background: #f8fafc;
        color: #cbd5e1;
        border: 1px solid #f1f5f9;
        cursor: not-allowed;
    }
    .pagination svg {
        width: 12px !important;
        height: 12px !important;
        max-width: 12px;
        max-height: 12px;
        vertical-align: middle;
        display: inline-block;
    }
    .pagination-wrapper {
        background: white;
        padding: 1.25rem 1.5rem 1.5rem;
        border-top: 1px solid #f1f5f9;
        border-radius: 0 0 12px 12px;
    }
    .pagination-info {
        text-align: center;
        color: #64748b;
        font-size: .85rem;
        margin-bottom: 1rem;
        font-weight: 500;
    }
    .pagination-info strong { color: #1e3a8a; font-weight: 700; }

    /* Presentación limpia para datos */
    .inline-pre { background: #f8fafc; padding: .5rem; border-radius: 6px; border: 1px solid #eef2ff; font-family: 'Courier New', monospace; font-size: .85rem; margin: 0; overflow-x: auto; }
    .copy-btn { background: #e6f4ea; color: #065f46; border: none; padding: .25rem .5rem; border-radius: 6px; font-size: .75rem; cursor: pointer; }

    /* Modal estilo imagen 3 */
    .modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.45);
        display: none;
        z-index: 9998;
        backdrop-filter: blur(1px);
    }
    .modal {
        position: fixed;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        background: #fff;
        width: min(1100px, 96%);
        max-height: 88vh;
        overflow-y: auto;
        border-radius: 16px;
        box-shadow: 0 28px 70px rgba(2, 6, 23, 0.30);
        border: 1px solid #e2e8f0;
        display: none;
        z-index: 9999;
    }
    .modal-header {
        padding: 1.1rem 1.35rem;
        border-bottom: 1px solid #dbe3ef;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        position: relative;
    }
    .modal-body {
        padding: 1rem 1.35rem 1.35rem;
        background: #f8fafc;
    }
    .modal-close {
        background: transparent;
        border: none;
        font-size: 1.35rem;
        cursor: pointer;
        color: #475569;
        width: 36px;
        height: 36px;
        border-radius: 8px;
    }
    .modal-close:hover {
        background: #f1f5f9;
        color: #0f172a;
    }
    .modal-header-actions {
        margin-left: auto;
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }
    .modal-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .28rem .6rem;
        border-radius: 999px;
        background: #dbeafe;
        color: #1d4ed8;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .02em;
        text-transform: uppercase;
    }
    .modal-title-line {
        font-size: 1.1rem;
        font-weight: 800;
        color: #0f172a;
        margin-top: .35rem;
    }
    .modal-subtitle-line {
        color: #64748b;
        font-size: .9rem;
        margin-top: .2rem;
    }
    .modal-summary {
        margin-top: .85rem;
        display: flex;
        gap: .55rem;
        flex-wrap: wrap;
    }
    .summary-chip {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .42rem .7rem;
        border-radius: 999px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        color: #334155;
        font-size: .78rem;
        font-weight: 600;
        box-shadow: 0 1px 2px rgba(2, 6, 23, 0.04);
    }
    .summary-chip strong { color: #0f172a; font-weight: 700; }
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }
    .detail-panel {
        border-radius: 14px;
        padding: .9rem;
        border: 1px solid #dbeafe;
        background: linear-gradient(180deg, #f9fbff 0%, #ffffff 100%);
        box-shadow: 0 12px 28px rgba(30, 58, 138, 0.08);
    }
    .detail-panel.detail-old {
        border-color: #fee2e2;
        background: linear-gradient(180deg, #fff7f7 0%, #ffffff 100%);
    }
    .detail-panel-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: .65rem;
        font-weight: 700;
    }
    .detail-panel-title.new { color: #059669; }
    .detail-panel-title.old { color: #dc2626; }
    .detail-badge {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .28rem .55rem;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: .74rem;
        font-weight: 700;
    }
    .detail-panel.detail-old .detail-badge {
        background: #fef2f2;
        color: #b91c1c;
    }
    .detail-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 0;
        overflow: hidden;
    }
    .detail-table td {
        border-bottom: 1px solid #e7eef6;
        padding: .55rem .6rem;
        vertical-align: top;
        font-size: .92rem;
    }
    .detail-table td:first-child {
        width: 36%;
        font-weight: 600;
        color: #0f172a;
        text-transform: lowercase;
    }
    .detail-table tr:last-child td { border-bottom: none; }
    .detail-table tr.diff-row td {
        background: rgba(59, 130, 246, 0.08);
    }
    .detail-panel.detail-old .detail-table tr.diff-row td {
        background: rgba(220, 38, 38, 0.08);
    }
    .diff-pill {
        display: inline-flex;
        align-items: center;
        gap: .25rem;
        padding: .16rem .45rem;
        margin-left: .4rem;
        border-radius: 999px;
        font-size: .68rem;
        font-weight: 700;
        letter-spacing: .02em;
        vertical-align: middle;
    }
    .diff-pill.new {
        background: #dbeafe;
        color: #1d4ed8;
    }
    .diff-pill.old {
        background: #fee2e2;
        color: #b91c1c;
    }
    .detail-table td.value-changed {
        font-weight: 700;
    }
    .detail-table td .changed-value {
        display: inline-block;
        padding: .12rem .35rem;
        border-radius: 6px;
        background: rgba(59, 130, 246, 0.10);
    }
    .detail-panel.detail-old .detail-table td .changed-value {
        background: rgba(220, 38, 38, 0.10);
    }
    .browser-box {
        margin-top: 1rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: .55rem .65rem;
        font-size: .9rem;
        color: #334155;
    }

    @media (max-width: 900px) {
        .detail-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 640px) {
        .modal { width: 96%; }
    }
</style>

<div class="container-fluid" style="padding: 2rem;">
    <!-- Header -->
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.8rem; font-weight: 700; color: #1e293b; margin-bottom: .5rem;">
            <i class="bi bi-shield-lock-fill" style="color: #1e3a8a;"></i> Auditoría del Sistema
        </h1>
        <p style="color: #64748b; font-size: .95rem;">Registro completo de todas las acciones realizadas en el sistema</p>
    </div>

    <!-- Filtros -->
    <div class="filters-card">
        <form method="GET" action="{{ route('auditoria.index') }}">
            <div class="filters-grid">
                <div>
                    <label style="display: block; font-size: .85rem; font-weight: 600; color: #475569; margin-bottom: .5rem;">Acción</label>
                    <select name="accion" class="form-select form-select-sm">
                        <option value="">Todas las acciones</option>
                        @foreach($acciones as $accion)
                            <option value="{{ $accion }}" {{ request('accion') == $accion ? 'selected' : '' }}>{{ ucfirst($accion) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: .85rem; font-weight: 600; color: #475569; margin-bottom: .5rem;">Entidad</label>
                    <input type="text" name="entidad" class="form-control form-control-sm" placeholder="Ej: Evento, Certificado..." value="{{ request('entidad') }}">
                </div>
                <div>
                    <label style="display: block; font-size: .85rem; font-weight: 600; color: #475569; margin-bottom: .5rem;">Usuario</label>
                    <select name="user_id" class="form-select form-select-sm">
                        <option value="">Todos los usuarios</option>
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}" {{ request('user_id') == $usuario->id ? 'selected' : '' }}>{{ $usuario->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: .85rem; font-weight: 600; color: #475569; margin-bottom: .5rem;">Desde</label>
                    <input type="date" name="fecha_desde" class="form-control form-control-sm" value="{{ request('fecha_desde') }}">
                </div>
                <div>
                    <label style="display: block; font-size: .85rem; font-weight: 600; color: #475569; margin-bottom: .5rem;">Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control form-control-sm" value="{{ request('fecha_hasta') }}">
                </div>
            </div>
            <div style="display: flex; gap: .75rem; margin-top: 1rem;">
                <button type="submit" class="btn-filter"><i class="bi bi-funnel"></i> Aplicar Filtros</button>
                <a href="{{ route('auditoria.index') }}" class="btn-reset"><i class="bi bi-x-circle"></i> Limpiar</a>
            </div>
        </form>
    </div>

    <!-- Tabla de auditoría -->
    <div class="audit-table">
        @if($logs->count() > 0)
            <div style="border-radius: 12px; overflow: hidden;">
            <table>
                <thead>
                    <tr>
                        <th>Fecha y Hora</th>
                        <th>Usuario</th>
                        <th>Acción</th>
                        <th>Entidad</th>
                        <th>ID</th>
                        <th>IP</th>
                        <th>Detalles</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        <tr>
                            <td>
                                <div class="timestamp">
                                    <i class="bi bi-calendar-event"></i> {{ $log->created_at->format('d/m/Y') }}<br>
                                    <i class="bi bi-clock"></i> {{ $log->created_at->format('H:i:s') }}
                                </div>
                            </td>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">{{ $log->user ? strtoupper(substr($log->user->name, 0, 2)) : 'SY' }}</div>
                                    <div>
                                        <div class="user-name">{{ $log->user ? $log->user->name : 'Sistema' }}</div>
                                        @if($log->user && $log->user->email)
                                            <div style="font-size: .7rem; color: #94a3b8;">{{ $log->user->email }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $badgeClass = 'badge-default';
                                    $accionTexto = ucfirst(str_replace('_', ' ', $log->accion));
                                    switch($log->accion) {
                                        case 'crear':
                                        case 'crear_memorandum':
                                        case 'crear_evento':
                                        case 'emitir_certificado':
                                        case 'generar_memorandum':
                                        case 'generar_certificado':
                                            $badgeClass = 'badge-crear';
                                            if ($log->accion === 'generar_memorandum') {
                                                $accionTexto = 'Generar memorándum';
                                            } elseif ($log->accion === 'generar_certificado') {
                                                $accionTexto = 'Generar certificado';
                                            }
                                            break;
                                        case 'actualizar':
                                        case 'actualizar_memorandum':
                                        case 'actualizar_evento':
                                            $badgeClass = 'badge-actualizar';
                                            break;
                                        case 'eliminar':
                                        case 'eliminar_memorandum':
                                        case 'eliminar_evento':
                                            $badgeClass = 'badge-eliminar';
                                            break;
                                        case 'login':
                                            $badgeClass = 'badge-login';
                                            break;
                                        case 'logout':
                                            $badgeClass = 'badge-logout';
                                            break;
                                    }
                                @endphp
                                    <span class="badge-accion {{ $badgeClass }}">{{ $accionTexto }}</span>
                            </td>
                            <td><span class="entity-badge">{{ class_basename($log->entidad) }}</span></td>
                            <td>{{ $log->entidad_id ?? '-' }}</td>
                            <td><span style="font-family: 'Courier New', monospace; font-size: .75rem; color: #64748b;">{{ $log->ip ?? '-' }}</span></td>
                            <td>
                                @if($log->datos_anteriores || $log->datos_nuevos)
                                    <button class="details-toggle" 
                                        onclick="openDetailsModal(this)"
                                        data-log-id="{{ $log->id }}"
                                        data-accion="{{ $log->accion }}"
                                        data-usuario="{{ $log->user ? $log->user->name : 'Sistema' }}"
                                        data-fecha="{{ $log->created_at->format('d/m/Y H:i:s') }}"
                                        data-entidad="{{ class_basename($log->entidad) }}"
                                        data-entidad-id="{{ $log->entidad_id ?? '-' }}"
                                        data-ip="{{ $log->ip ?? '-' }}"
                                        data-datos-nuevos-b64="{{ base64_encode(json_encode($log->datos_nuevos, JSON_UNESCAPED_UNICODE)) }}"
                                        data-datos-anteriores-b64="{{ base64_encode(json_encode($log->datos_anteriores, JSON_UNESCAPED_UNICODE)) }}"
                                        data-user-agent="{{ htmlspecialchars($log->user_agent ?? '', ENT_QUOTES, 'UTF-8') }}"
                                    >
                                        <i class="bi bi-eye"></i> Ver detalles
                                    </button>
                                @else
                                    <span style="color: #cbd5e1;">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>

            <!-- Paginación -->
            @if($logs->hasPages())
                <div class="pagination-wrapper">
                    <div class="pagination">{{ $logs->links('vendor.pagination.clean') }}</div>
                </div>
            @endif
        @else
            <div class="no-results">
                <i class="bi bi-inbox"></i>
                <h3 style="color: #64748b; font-size: 1.2rem; margin-top: 1rem;">No se encontraron registros</h3>
                <p style="color: #94a3b8; font-size: .9rem;">Intenta ajustar los filtros o verifica que haya actividad en el sistema</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal backdrop and modal -->
<div id="modalBackdrop" class="modal-backdrop"></div>
<div id="detailsModal" class="modal" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-header">
        <div class="modal-header-actions">
            <button class="modal-close" onclick="closeDetailsModal()">✕</button>
        </div>
    </div>
    <div id="detailsModalContent" class="modal-body">
        <!-- Contenido generado dinámicamente -->
    </div>
</div>

<script>
    function openDetailsModal(button) {
        const dataset = button.dataset;
        const modal = document.getElementById('detailsModal');
        const backdrop = document.getElementById('modalBackdrop');
        const content = document.getElementById('detailsModalContent');

        const usuario = dataset.usuario || '';
        const accion = dataset.accion ? dataset.accion.replace(/_/g, ' ') : '';
        const fecha = dataset.fecha || '';
        const entidad = dataset.entidad || '';
        const entidadId = dataset.entidadId || dataset.entidadId === '0' ? dataset.entidadId : (dataset.entidadId || '');
        const ip = dataset.ip || '';
        const userAgent = dataset.userAgent || '';

        const nuevos = parseEncodedJson(dataset.datosNuevosB64);
        const anteriores = parseEncodedJson(dataset.datosAnterioresB64);
        const mostrarAnteriores = hasMeaningfulPreviousData(anteriores, nuevos);

        let html = '';
        html += '<div class="modal-eyebrow">Auditoría del sistema</div>';
        html += '<div class="modal-title-line">' + escapeHtml(usuario) + '</div>';
        html += '<div class="modal-subtitle-line">' + escapeHtml(accion) + ' · ' + escapeHtml(fecha) + '</div>';
        html += '<div class="modal-summary">';
        html += '<span class="summary-chip"><strong>Entidad:</strong> ' + escapeHtml(entidad) + '</span>';
        html += '<span class="summary-chip"><strong>ID:</strong> ' + escapeHtml(entidadId) + '</span>';
        html += '<span class="summary-chip"><strong>IP:</strong> ' + escapeHtml(ip) + '</span>';
        html += '</div>';

        html += '<div class="detail-grid" style="grid-template-columns:' + (mostrarAnteriores ? 'repeat(2, minmax(0, 1fr))' : '1fr') + ';">';

        html += '<div class="detail-panel">';
        html += '<div class="detail-panel-title new"><span class="detail-badge">📝 Datos Nuevos</span><button class="copy-btn" onclick="copyModalJson(\'' + (dataset.logId || '') + '\', true)">Copiar JSON</button></div>';
        html += renderKeyValueTable(nuevos, mostrarAnteriores ? anteriores : {}, 'new');
        html += '</div>';

        if (mostrarAnteriores) {
            html += '<div class="detail-panel detail-old">';
            html += '<div class="detail-panel-title old"><span class="detail-badge">📋 Datos Anteriores</span><button class="copy-btn" style="background:#fdecea;color:#7f1d1d;" onclick="copyModalJson(\'' + (dataset.logId || '') + '\', false)">Copiar JSON</button></div>';
            html += renderKeyValueTable(anteriores, nuevos, 'old');
            html += '</div>';
        }

        html += '</div>';

        if (userAgent) {
            html += '<div style="margin-top:1rem;">';
            html += '<strong>🖥️ Navegador:</strong>';
            html += '<div class="browser-box">' + escapeHtml(userAgent) + '</div>';
            html += '</div>';
        }

        content.innerHTML = html;

        modal.style.display = 'block';
        backdrop.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function parseEncodedJson(encoded) {
        if (!encoded) return {};
        try {
            const decoded = decodeBase64Unicode(encoded);
            const parsed = JSON.parse(decoded);
            return normalizeData(parsed);
        } catch (error) {
            return {};
        }
    }

    function decodeBase64Unicode(value) {
        const binary = atob(value);
        const bytes = Uint8Array.from(binary, char => char.charCodeAt(0));
        return new TextDecoder('utf-8').decode(bytes);
    }

    function normalizeData(value) {
        if (value === null || typeof value === 'undefined') return {};
        if (typeof value === 'string') {
            const trimmed = value.trim();
            if (!trimmed) return {};
            try {
                return normalizeData(JSON.parse(trimmed));
            } catch (error) {
                return { valor: value };
            }
        }
        if (typeof value === 'object') return value;
        return { valor: value };
    }

    function hasMeaningfulPreviousData(previousData, newData) {
        if (!previousData || Object.keys(previousData).length === 0) return false;
        if (!newData || Object.keys(newData).length === 0) return true;
        return JSON.stringify(previousData) !== JSON.stringify(newData);
    }

    function renderKeyValueTable(obj, compareObj = {}, side = 'new') {
        if (!obj || Object.keys(obj).length === 0) return '<div style="color:#94a3b8; margin-top:.5rem;">— Sin datos —</div>';
        let table = '<table class="detail-table">';
        Object.keys(obj).forEach(function(key) {
            const val = obj[key];
            const compareVal = compareObj ? compareObj[key] : undefined;
            const isChanged = compareObj && Object.keys(compareObj).length > 0 && !valuesAreEqual(val, compareVal);
            const pillClass = side === 'old' ? 'old' : 'new';
            table += '<tr class="' + (isChanged ? 'diff-row' : '') + '">';
            table += '<td>' + escapeHtml(key) + (isChanged ? '<span class="diff-pill ' + pillClass + '">cambio</span>' : '') + '</td>';
            table += '<td class="' + (isChanged ? 'value-changed' : '') + '">';
            if (val === null || val === '') {
                table += '—';
            } else if (typeof val === 'object') {
                table += '<pre class="inline-pre ' + (isChanged ? 'changed-value' : '') + '" style="margin:0;">' + escapeHtml(JSON.stringify(val, null, 2)) + '</pre>';
            } else {
                table += '<span class="' + (isChanged ? 'changed-value' : '') + '">' + escapeHtml(String(val)) + '</span>';
            }
            table += '</td>';
            table += '</tr>';
        });
        table += '</table>';
        return table;
    }

    function valuesAreEqual(a, b) {
        return normalizeComparable(a) === normalizeComparable(b);
    }

    function normalizeComparable(value) {
        if (value === null || typeof value === 'undefined') return '__null__';
        if (typeof value === 'object') {
            try {
                return JSON.stringify(value);
            } catch (error) {
                return String(value);
            }
        }
        return String(value).trim();
    }

    function escapeHtml(str) {
        if (str === null || typeof str === 'undefined') return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function closeDetailsModal() {
        const modal = document.getElementById('detailsModal');
        const backdrop = document.getElementById('modalBackdrop');
        modal.style.display = 'none';
        backdrop.style.display = 'none';
        document.body.style.overflow = '';
    }

    function copyModalJson(logId, isNuevos) {
        // Buscar el botón original con el dataset
        const selector = '[data-log-id="' + logId + '"]';
        const btn = document.querySelector(selector);
        if (!btn) return alert('No se encontraron datos para copiar');
        const parsed = isNuevos
            ? parseEncodedJson(btn.dataset.datosNuevosB64)
            : parseEncodedJson(btn.dataset.datosAnterioresB64);
        if (!parsed || Object.keys(parsed).length === 0) return alert('No hay datos para copiar');
        const text = JSON.stringify(parsed, null, 2);
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(() => alert('JSON copiado al portapapeles'));
        } else {
            const ta = document.createElement('textarea'); ta.value = text; document.body.appendChild(ta); ta.select();
            try { document.execCommand('copy'); alert('JSON copiado al portapapeles'); } catch (e) { alert('No se pudo copiar'); }
            document.body.removeChild(ta);
        }
    }

    document.getElementById('modalBackdrop').addEventListener('click', closeDetailsModal);
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') closeDetailsModal();
    });
</script>
@endsection

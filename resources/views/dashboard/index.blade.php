{{--
|--------------------------------------------------------------------------
| Dashboard de Administración – Sistema de Certificaciones y Memorándums
|--------------------------------------------------------------------------
|
| VALIDACIÓN DE ROL:
|  • La ruta que sirve esta vista usa middleware('role:admin').
|  • Además, dentro de la misma vista se comprueba @role('admin') para
|    mostrar "Acceso Denegado" si por algún motivo llega un usuario sin
|    el rol correcto.
|
--}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard · Certificaciones Digitales</title>
    <link rel="icon" href="{{ asset('img/104/logo_medicina_sf.png') }}">
    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ── Variables globales ───────────────────────── */
        :root {
            --sidebar-w: 240px;
            --header-h: 60px;
            --blue-dark: #1e3a8a;
            --blue-darker: #172554;
            --red-accent: #dc2626;
            --gray-bg: #f1f5f9;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--gray-bg);
            color: #1e293b;
            overflow-x: hidden;
        }

        /* ── HEADER ─────────────────────────────────── */
        .dash-header {
            position: fixed; top: 0; left: var(--sidebar-w); right: 0;
            height: var(--header-h);
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 1.5rem;
            z-index: 1000;
        }
        .dash-header .brand { display: flex; align-items: center; gap: .75rem; }
        .dash-header .brand img { height: 38px; }
        .dash-header .brand-text h1 { font-size: .82rem; font-weight: 700; color: var(--blue-dark); margin: 0; line-height: 1.2; }
        .dash-header .brand-text p  { font-size: .68rem; color: #64748b; margin: 0; }
        .dash-header .search-box { flex: 0 1 340px; }
        .dash-header .search-box input {
            width: 100%; border: 1px solid #e2e8f0; border-radius: 8px;
            padding: .45rem .85rem .45rem 2.2rem; font-size: .8rem; background: #f8fafc;
            transition: border .2s;
        }
        .dash-header .search-box input:focus { outline: none; border-color: var(--blue-dark); background: #fff; }
        .dash-header .search-wrap { position: relative; }
        .dash-header .search-wrap i {
            position: absolute; left: .7rem; top: 50%; transform: translateY(-50%);
            color: #94a3b8; font-size: .85rem;
        }
        .dash-header .profile { display: flex; align-items: center; gap: .6rem; }
        .dash-header .profile .avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: linear-gradient(135deg, var(--blue-dark), var(--blue-darker));
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: .8rem;
        }
        .dash-header .profile .info { text-align: right; }
        .dash-header .profile .info .uname { font-size: .78rem; font-weight: 600; color: #1e293b; }
        .dash-header .profile .info .urole { font-size: .68rem; color: #64748b; }

        /* ── SIDEBAR ────────────────────────────────── */
        .dash-sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: var(--sidebar-w);
            background: linear-gradient(180deg, var(--blue-dark) 0%, var(--blue-darker) 100%);
            color: #fff; display: flex; flex-direction: column;
            z-index: 1100; padding-top: 0;
            overflow-y: auto;
        }
        .sidebar-logo {
            padding: .85rem 1rem; display: flex; align-items: center; gap: .55rem;
            border-bottom: 1px solid rgba(255,255,255,.1);
        }
        .sidebar-logo img { height: 32px; border-radius: 50%; background: #fff; padding: 2px; }
        .sidebar-logo span { font-size: .78rem; font-weight: 700; line-height: 1.2; }
        .sidebar-nav { flex: 1; padding: .8rem 0; }
        .sidebar-nav a {
            display: flex; align-items: center; gap: .65rem;
            padding: .6rem 1.3rem; color: rgba(255,255,255,.7);
            text-decoration: none; font-size: .8rem; font-weight: 500;
            transition: all .2s; border-left: 3px solid transparent;
        }
        .sidebar-nav a:hover { background: rgba(255,255,255,.08); color: #fff; }
        .sidebar-nav a.active {
            background: rgba(255,255,255,.12); color: #fff;
            border-left-color: #60a5fa;
        }
        .sidebar-nav a i { font-size: 1.05rem; width: 22px; text-align: center; }
        .sidebar-nav .section-label {
            padding: .55rem 1.3rem .25rem; font-size: .65rem;
            text-transform: uppercase; letter-spacing: .08em;
            color: rgba(255,255,255,.4); font-weight: 600;
        }
        .sidebar-user {
            margin: .5rem .7rem; padding: .5rem .7rem;
            background: rgba(255,255,255,.08); border-radius: 8px;
        }
        .sidebar-user .name { font-size: .72rem; font-weight: 600; }
        .sidebar-user .status {
            font-size: .64rem; color: rgba(255,255,255,.6);
            display: flex; align-items: center; gap: .3rem; margin-top: .1rem;
        }
        .sidebar-user .status .dot { width: 6px; height: 6px; border-radius: 50%; background: #22c55e; display: inline-block; }

        /* ── MAIN CONTENT ───────────────────────────── */
        .dash-main {
            margin-left: var(--sidebar-w);
            margin-top: var(--header-h);
            padding: 1.4rem 1.6rem 2rem;
            min-height: calc(100vh - var(--header-h));
        }

        /* ── CARDS ──────────────────────────────────── */
        .dash-card {
            background: #fff; border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
            border: 1px solid #e2e8f0; overflow: hidden;
            height: 100%; display: flex; flex-direction: column;
        }
        .dash-card .card-head {
            padding: .75rem 1rem; border-bottom: 1px solid #f1f5f9;
            font-size: .72rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: .04em; color: #475569;
            display: flex; align-items: center; gap: .5rem;
        }
        .dash-card .card-body { padding: 1rem; flex: 1; }

        /* Stat number */
        .stat-number { font-size: 2.6rem; font-weight: 800; color: var(--blue-dark); line-height: 1; }
        .stat-label  { font-size: .72rem; color: #64748b; margin-top: .3rem; }

        /* KPI cards */
        .kpi-card { text-align: center; padding: 1.2rem .8rem; }
        .kpi-card .kpi-icon {
            width: 44px; height: 44px; border-radius: 10px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 1.2rem; margin-bottom: .6rem;
        }
        .kpi-card .kpi-value { font-size: 1.8rem; font-weight: 800; color: #1e293b; }
        .kpi-card .kpi-label { font-size: .7rem; color: #64748b; margin-top: .2rem; font-weight: 500; }

        /* Solicitudes list */
        .sol-item {
            display: flex; align-items: flex-start; gap: .6rem;
            padding: .6rem 0; border-bottom: 1px solid #f1f5f9;
        }
        .sol-item:last-child { border-bottom: none; }
        .sol-item .sol-icon {
            width: 32px; height: 32px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: .85rem; flex-shrink: 0;
        }
        .sol-item .sol-text { font-size: .76rem; color: #334155; line-height: 1.4; }
        .sol-item .sol-text strong { color: #1e293b; }

        /* Alert items */
        .alert-item {
            display: flex; align-items: flex-start; gap: .6rem;
            padding: .55rem 0; border-bottom: 1px solid #f1f5f9;
        }
        .alert-item:last-child { border-bottom: none; }
        .alert-item .alert-icon {
            width: 30px; height: 30px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: .8rem; flex-shrink: 0;
        }
        .alert-item .alert-text { font-size: .76rem; color: #334155; line-height: 1.4; }
        .alert-item .alert-tag {
            font-size: .62rem; font-weight: 600; text-transform: uppercase;
            letter-spacing: .03em; margin-top: .15rem; display: inline-block;
        }

        /* Activity log */
        .activity-scroll {
            max-height: 220px; overflow-y: auto;
            scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent;
        }
        .activity-scroll::-webkit-scrollbar { width: 4px; }
        .activity-scroll::-webkit-scrollbar-track { background: transparent; }
        .activity-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .activity-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .activity-item {
            display: flex; align-items: flex-start; gap: .6rem;
            padding: .5rem 0; border-bottom: 1px solid #f1f5f9;
            font-size: .76rem; color: #475569;
        }
        .activity-item:last-child { border-bottom: none; }
        .activity-item .act-dot {
            width: 8px; height: 8px; border-radius: 50%;
            flex-shrink: 0; margin-top: .35rem;
        }

        /* Trend mini-chart */
        .trend-chart { width: 100%; margin-top: .8rem; }

        /* Btn accent */
        .btn-accent {
            background: var(--red-accent); color: #fff; border: none;
            font-weight: 600; font-size: .78rem; border-radius: 8px;
            padding: .55rem 1rem; width: 100%; transition: background .2s;
        }
        .btn-accent:hover { background: #b91c1c; color: #fff; }

        /* Logout inside sidebar */
        .sidebar-logout {
            padding: .35rem .9rem .6rem; margin-top: auto;
        }
        .sidebar-logout button {
            background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.15);
            color: rgba(255,255,255,.8); width: 100%; border-radius: 6px;
            padding: .35rem; font-size: .7rem; font-weight: 500;
            cursor: pointer; transition: all .2s;
        }
        .sidebar-logout button:hover { background: rgba(255,255,255,.15); color: #fff; }

        /* ── ACCESS DENIED ──────────────────────────── */
        .access-denied {
            display: flex; flex-direction: column; align-items: center;
            justify-content: center; min-height: 100vh; text-align: center;
            background: var(--gray-bg);
        }
        .access-denied i { font-size: 4rem; color: var(--red-accent); margin-bottom: 1rem; }
        .access-denied h2 { font-size: 1.5rem; font-weight: 700; color: #1e293b; }
        .access-denied p  { color: #64748b; max-width: 400px; margin-top: .5rem; }

        /* ── RESPONSIVE ─────────────────────────────── */
        @media (max-width: 1024px) {
            :root { --sidebar-w: 210px; }
        }
        @media (max-width: 768px) {
            :root { --sidebar-w: 0px; --header-h: 56px; }
            .dash-sidebar { transform: translateX(-100%); transition: transform .3s; width: 240px; }
            .dash-sidebar.open { transform: translateX(0); }
            .dash-header { left: 0; }
            .dash-header .brand-text p { display: none; }
            .dash-header .search-box { flex: 0 1 200px; }
            .mobile-toggle { display: inline-flex !important; }
        }
        @media (max-width: 576px) {
            .dash-header .search-box { display: none; }
            .dash-main { padding: 1rem; }
        }
        .mobile-toggle {
            display: none !important; background: none; border: none;
            font-size: 1.3rem; color: #475569; cursor: pointer; margin-right: .5rem;
        }
    </style>
</head>
<body>

{{--
|--------------------------------------------------------------------------
| VALIDACIÓN DE ROL (client-side / Blade)
|--------------------------------------------------------------------------
| Se verifica con @role('admin') de Spatie. Si el usuario autenticado NO
| tiene el rol 'admin', se muestra la pantalla de "Acceso Denegado" y
| se oculta todo el dashboard.
--}}

@role('admin')

{{-- ═══════════════════════════════════════════════════════
     SIDEBAR
     ═══════════════════════════════════════════════════════ --}}
<aside class="dash-sidebar" id="sidebar">
    <div class="sidebar-logo">
        <img src="{{ asset('img/104/logo_medicina_sf.png') }}" alt="Logo Medicina">
        <span>Facultad de<br>Medicina U.M.S.A.</span>
    </div>

    <nav class="sidebar-nav">
        <div class="section-label">Navegación</div>
        <a href="{{ url('/dashboard') }}" class="active">
            <i class="bi bi-grid-1x2-fill"></i> Panel de Control
        </a>
        <a href="{{ route('eventos.index') }}">
            <i class="bi bi-award-fill"></i> Certificados
        </a>
        <a href="{{ route('memorandums.index') }}">
            <i class="bi bi-file-earmark-text-fill"></i> Memorándums
        </a>
        <a href="{{ route('usuarios.index') }}">
            <i class="bi bi-people-fill"></i> Usuarios
        </a>

        <div class="section-label" style="margin-top:1rem;">Sistema</div>
        
        <a href="{{ route('reportes.index') }}" class="{{ ($activePage ?? '') === 'reportes' ? 'active' : '' }}">
            <i class="bi bi-file-earmark-bar-graph"></i> Reportes
        </a>
        <a href="{{ route('auditoria.index') }}">
            <i class="bi bi-shield-lock-fill"></i> Auditoría
        </a>

    </nav>

    {{-- Recuadro de usuario --}}
    <div class="sidebar-user">
        <div class="name">Bienvenido, {{ Auth::user()->name }}</div>
        <div class="status">
            <span class="dot"></span> Conectado
        </div>
    </div>

    {{-- Cerrar sesión --}}
    <div class="sidebar-logout">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"><i class="bi bi-box-arrow-left me-1"></i> Cerrar Sesión</button>
        </form>
    </div>
</aside>

{{-- ═══════════════════════════════════════════════════════
     HEADER
     ═══════════════════════════════════════════════════════ --}}
<header class="dash-header">
    <div class="d-flex align-items-center">
        <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')">
            <i class="bi bi-list"></i>
        </button>
        <div class="brand">
            <img src="{{ asset('img/104/logo_medicina_sf.png') }}" alt="Logo">
            <div class="brand-text">
                <h1>CARRERA DE MEDICINA</h1>
                <p>Sistema de Certificaciones y Memorándums Digitales</p>
            </div>
        </div>
    </div>

    <div class="search-box d-none d-md-block">
        <form action="{{ route('buscar') }}" method="GET" class="search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" name="q" placeholder="Buscar certificados, memorándums, usuarios…" value="{{ request('q') }}">
        </form>
    </div>

    <div class="profile">
        <div class="info d-none d-sm-block">
            <div class="uname">{{ Auth::user()->name }}</div>
            <div class="urole">Administrador Principal</div>
        </div>
        <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
    </div>
</header>

{{-- ═══════════════════════════════════════════════════════
     CONTENIDO PRINCIPAL
     ═══════════════════════════════════════════════════════ --}}
<main class="dash-main">
    {{-- Título --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 style="font-weight:700; font-size:1.1rem; margin:0;">Panel de Control</h4>
            <p style="font-size:.74rem; color:#64748b; margin:0;">Resumen general del sistema — {{ now()->translatedFormat('d \\d\\e F, Y') }}</p>
        </div>

    </div>

    {{-- ── ROW 1: 3 CARDS PRINCIPALES ─────────────── --}}
    <div class="row g-3 mb-3">

        {{-- CARD 1 — Estadísticas Generales --}}
        <div class="col-lg-4">
            <div class="dash-card">
                <div class="card-head">
                    <i class="bi bi-bar-chart-line-fill" style="color:var(--blue-dark)"></i>
                    Estadísticas Generales del Sistema
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div>
                            <div class="stat-number">{{ number_format($totalCertificados) }}</div>
                            <div class="stat-label">Certificados emitidos en total</div>
                        </div>
                        <div style="font-size:2.8rem; color:#e2e8f0;">
                            <i class="bi bi-folder2-open"></i>
                        </div>
                    </div>
                    {{-- Gráfico de tendencia SVG --}}
                    <div class="trend-chart">
                        <svg viewBox="0 0 320 80" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:100%;height:auto;">
                            <defs>
                                <linearGradient id="trendGrad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#1e3a8a" stop-opacity="0.15"/>
                                    <stop offset="100%" stop-color="#1e3a8a" stop-opacity="0"/>
                                </linearGradient>
                            </defs>
                            <path d="M0,65 Q30,60 60,50 T120,38 T180,28 T240,18 T320,5" stroke="#1e3a8a" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                            <path d="M0,65 Q30,60 60,50 T120,38 T180,28 T240,18 T320,5 L320,80 L0,80 Z" fill="url(#trendGrad)"/>
                            {{-- Puntos de datos --}}
                            <circle cx="0" cy="65" r="3" fill="#1e3a8a"/>
                            <circle cx="60" cy="50" r="3" fill="#1e3a8a"/>
                            <circle cx="120" cy="38" r="3" fill="#1e3a8a"/>
                            <circle cx="180" cy="28" r="3" fill="#1e3a8a"/>
                            <circle cx="240" cy="18" r="3" fill="#1e3a8a"/>
                            <circle cx="320" cy="5" r="3.5" fill="#dc2626"/>
                        </svg>
                        <div class="d-flex justify-content-between" style="font-size:.62rem; color:#94a3b8; margin-top:.2rem;">
                            <span>Ene</span><span>Mar</span><span>May</span><span>Jul</span><span>Sep</span><span>Hoy</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 2 — Solicitudes y Memorándums Recientes (datos de la BD) --}}
        <div class="col-lg-4">
            <div class="dash-card">
                <div class="card-head">
                    <i class="bi bi-journal-text" style="color:#7c3aed"></i>
                    Solicitudes y Memorándums Recientes
                </div>
                <div class="card-body d-flex flex-column">
                    @forelse($ultimosMemos as $memo)
                    <div class="sol-item">
                        <div class="sol-icon" style="background:#ede9fe; color:#7c3aed;">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <div class="sol-text">
                            <strong>Memorándum nº {{ $memo->id }}:</strong> {{ Str::limit($memo->evento, 55) }}
                            @if($memo->catedra)
                                <span style="font-size:.68rem; color:#64748b;">({{ $memo->catedra }})</span>
                            @endif
                            <span style="display:block; font-size:.66rem; color:#94a3b8;">{{ $memo->created_at?->translatedFormat('d M. Y') ?? 'Sin fecha' }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-3" style="color:#94a3b8; font-size:.78rem;">
                        <i class="bi bi-inbox" style="font-size:1.5rem; display:block; margin-bottom:.3rem;"></i>
                        No hay memorándums registrados aún.
                    </div>
                    @endforelse

                    <div class="mt-auto pt-3">
                        <a href="{{ route('memorandums.create') }}" class="btn btn-accent">
                            <i class="bi bi-plus-circle me-1"></i> CREAR MEMORANDUM
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 3 — Alertas y Notificaciones (basadas en datos reales de la BD) --}}
        <div class="col-lg-4">
            <div class="dash-card">
                <div class="card-head">
                    <i class="bi bi-bell-fill" style="color:#f59e0b"></i>
                    Alertas y Notificaciones del Sistema
                </div>
                <div class="card-body">
                    {{-- Alertas dinámicas: certificados pendientes de validación --}}
                    @forelse($certPendientes as $cert)
                    <div class="alert-item">
                        <div class="alert-icon" style="background:#fef3c7; color:#f59e0b;">
                            <i class="bi bi-bell-fill"></i>
                        </div>
                        <div>
                            <div class="alert-text">
                                Certificación de <strong>{{ $cert->nombre }} {{ $cert->paterno }}</strong> pendiente de validación.
                                @if($cert->evento)
                                    <span style="font-size:.68rem; color:#64748b;">({{ Str::limit($cert->evento->evento, 30) }})</span>
                                @endif
                            </div>
                            <span class="alert-tag" style="color:#f59e0b;">Pendiente</span>
                        </div>
                    </div>
                    @empty
                    <div class="alert-item">
                        <div class="alert-icon" style="background:#dcfce7; color:#16a34a;">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div>
                            <div class="alert-text">No hay certificaciones <strong>pendientes</strong> de validación.</div>
                            <span class="alert-tag" style="color:#16a34a;">Todo al día</span>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- ── ROW 2: 3 KPI + 1 ACTIVIDAD ────────────── --}}
    <div class="row g-3">

        {{-- KPI 1 — Solicitudes Pendientes --}}
        <div class="col-sm-6 col-lg-3">
            <div class="dash-card">
                <div class="card-body kpi-card">
                    <div class="kpi-icon" style="background:#dbeafe; color:#1e3a8a;">
                        <i class="bi bi-file-earmark-arrow-up"></i>
                    </div>
                    <div class="kpi-value">{{ $solicitudesPendientes }}</div>
                    <div class="kpi-label">Total Solicitudes Pendientes Actuales</div>
                </div>
            </div>
        </div>

        {{-- KPI 2 — Memorándums Recientes --}}
        <div class="col-sm-6 col-lg-3">
            <div class="dash-card">
                <div class="card-body kpi-card">
                    <div class="kpi-icon" style="background:#ede9fe; color:#7c3aed;">
                        <i class="bi bi-envelope-paper-fill"></i>
                    </div>
                    <div class="kpi-value">{{ $memosRecientes }}</div>
                    <div class="kpi-label">Memorándums Recientes (Últimos 30 días)</div>
                </div>
            </div>
        </div>

        {{-- KPI 3 — Usuarios Activos --}}
        <div class="col-sm-6 col-lg-3">
            <div class="dash-card">
                <div class="card-body kpi-card">
                    <div class="kpi-icon" style="background:#dcfce7; color:#16a34a;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="kpi-value">{{ $usuariosActivos }}</div>
                    <div class="kpi-label">Usuarios Activos del Sistema</div>
                </div>
            </div>
        </div>

        {{-- CARD — Resumen de Actividades Recientes (desde audit_logs) --}}
        <div class="col-sm-6 col-lg-3">
            <div class="dash-card">
                <div class="card-head">
                    <i class="bi bi-clock-history" style="color:#0ea5e9"></i>
                    Resumen de Actividades Recientes
                </div>
                <div class="card-body activity-scroll" style="padding:.75rem 1rem;">
                    @forelse($ultimasActividades as $actividad)
                    @php
                        // Color del punto según acción
                        $dotColor = match($actividad->accion) {
                            'crear'     => '#1e3a8a',
                            'actualizar'=> '#7c3aed',
                            'eliminar'  => '#dc2626',
                            'login'     => '#16a34a',
                            default     => '#64748b',
                        };
                        // Nombre corto de la entidad (sin namespace)
                        $entidadCorta = class_basename($actividad->entidad);
                    @endphp
                    <div class="activity-item">
                        <div class="act-dot" style="background:{{ $dotColor }};"></div>
                        <div>
                            <strong>[{{ $actividad->user?->name ?? 'Sistema' }}]</strong>
                            {{ $actividad->accion }} {{ $entidadCorta }}
                            @if($actividad->entidad_id) nº {{ $actividad->entidad_id }} @endif
                            <span style="display:block; font-size:.64rem; color:#94a3b8;">{{ $actividad->created_at?->translatedFormat('d M. Y · H:i') ?? 'Sin fecha' }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-3" style="color:#94a3b8; font-size:.78rem;">
                        <i class="bi bi-clock" style="font-size:1.2rem; display:block; margin-bottom:.3rem;"></i>
                        No hay actividades registradas aún.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</main>

@else
{{-- ═══════════════════════════════════════════════════════
     ACCESO DENEGADO (si el usuario NO tiene rol 'admin')
     ═══════════════════════════════════════════════════════ --}}
<div class="access-denied">
    <i class="bi bi-shield-lock-fill"></i>
    <h2>Acceso Denegado</h2>
    <p>No tienes permisos para acceder al Panel de Administración. Esta sección está restringida al rol de <strong>Administrador Principal</strong>.</p>
    <a href="{{ url('/') }}" class="btn btn-accent mt-3" style="width:auto; padding:.5rem 1.5rem;">
        <i class="bi bi-arrow-left me-1"></i> Volver al Inicio
    </a>
    <form method="POST" action="{{ route('logout') }}" class="mt-2">
        @csrf
        <button type="submit" style="background:none; border:none; color:#64748b; font-size:.82rem; cursor:pointer; text-decoration:underline;">
            Cerrar Sesión
        </button>
    </form>
</div>
@endrole

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

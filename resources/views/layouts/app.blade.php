{{--
|--------------------------------------------------------------------------
| Layout base para páginas del sistema con sidebar + header
|--------------------------------------------------------------------------
| Uso:  @extends('layouts.app')
|       @section('title', 'Mi Título')
|       @section('content') … @endsection
|
| Variables opcionales:
|   $activePage  →  'panel' | 'certificados' | 'memorandums' | 'usuarios' | 'auditoria' | 'configuracion'
--}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Certificaciones Digitales')</title>
    <link rel="icon" href="{{ asset('img/104/logo_medicina_sf.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
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

        /* ── HEADER ───────────────────────────── */
        .dash-header {
            position: fixed; top: 0; left: var(--sidebar-w); right: 0;
            height: var(--header-h); background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 1.5rem; z-index: 1000;
        }
        .dash-header .brand { display: flex; align-items: center; gap: .75rem; }
        .dash-header .brand img { height: 38px; }
        .dash-header .brand-text h1 { font-size: .82rem; font-weight: 700; color: var(--blue-dark); margin: 0; line-height: 1.2; }
        .dash-header .brand-text p  { font-size: .68rem; color: #64748b; margin: 0; }
        .dash-header .search-box { flex: 0 1 340px; }
        .dash-header .search-box input {
            width: 100%; border: 1px solid #e2e8f0; border-radius: 8px;
            padding: .45rem .85rem .45rem 2.2rem; font-size: .8rem; background: #f8fafc;
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

        /* ── SIDEBAR ──────────────────────────── */
        .dash-sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: var(--sidebar-w);
            background: linear-gradient(180deg, var(--blue-dark) 0%, var(--blue-darker) 100%);
            color: #fff; display: flex; flex-direction: column;
            z-index: 1100; overflow-y: auto;
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
        .sidebar-nav a.active { background: rgba(255,255,255,.12); color: #fff; border-left-color: #60a5fa; }
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
        .sidebar-logout { padding: .35rem .9rem .6rem; margin-top: auto; }
        .sidebar-logout button {
            background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.15);
            color: rgba(255,255,255,.8); width: 100%; border-radius: 6px;
            padding: .35rem; font-size: .7rem; font-weight: 500;
            cursor: pointer; transition: all .2s;
        }
        .sidebar-logout button:hover { background: rgba(255,255,255,.15); color: #fff; }

        /* ── MAIN ─────────────────────────────── */
        .dash-main {
            margin-left: var(--sidebar-w);
            margin-top: var(--header-h);
            padding: 1.4rem 1.6rem 2rem;
            min-height: calc(100vh - var(--header-h));
        }

        /* ── PAGE CARD (contenedor de formularios/tablas) ── */
        .page-card {
            background: #fff; border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
            border: 1px solid #e2e8f0; overflow: hidden;
        }
        .page-card .page-card-head {
            padding: .85rem 1.2rem; border-bottom: 1px solid #f1f5f9;
            display: flex; align-items: center; justify-content: space-between;
        }
        .page-card .page-card-head h2 {
            font-size: .85rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: .03em; color: #1e293b; margin: 0;
            display: flex; align-items: center; gap: .5rem;
        }
        .page-card .page-card-body { padding: 1.2rem; }

        /* ── FORM STYLES ──────────────────────── */
        .form-label-custom { font-size: .78rem; font-weight: 600; color: #334155; margin-bottom: .3rem; }
        .form-control-custom {
            border: 1px solid #e2e8f0; border-radius: 8px; padding: .5rem .75rem;
            font-size: .8rem; transition: border .2s; width: 100%;
        }
        .form-control-custom:focus { outline: none; border-color: var(--blue-dark); box-shadow: 0 0 0 3px rgba(30,58,138,.1); }
        .form-hint { font-size: .68rem; color: #94a3b8; margin-top: .2rem; }

        /* ── BUTTONS ──────────────────────────── */
        .btn-primary-custom {
            background: var(--blue-dark); color: #fff; border: none;
            font-weight: 600; font-size: .78rem; border-radius: 8px;
            padding: .5rem 1.2rem; transition: background .2s;
            cursor: pointer;
        }
        .btn-primary-custom:hover { background: var(--blue-darker); color: #fff; }
        .btn-secondary-custom {
            background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;
            font-weight: 500; font-size: .78rem; border-radius: 8px;
            padding: .5rem 1.2rem; transition: all .2s;
            text-decoration: none; cursor: pointer;
        }
        .btn-secondary-custom:hover { background: #e2e8f0; color: #1e293b; }
        .btn-danger-custom {
            background: var(--red-accent); color: #fff; border: none;
            font-weight: 600; font-size: .78rem; border-radius: 8px;
            padding: .5rem 1.2rem; transition: background .2s; cursor: pointer;
        }
        .btn-danger-custom:hover { background: #b91c1c; color: #fff; }
        .btn-success-custom {
            background: #16a34a; color: #fff; border: none;
            font-weight: 600; font-size: .78rem; border-radius: 8px;
            padding: .5rem 1.2rem; transition: background .2s; cursor: pointer;
        }
        .btn-success-custom:hover { background: #15803d; color: #fff; }
        .btn-info-custom {
            background: #0ea5e9; color: #fff; border: none;
            font-weight: 600; font-size: .78rem; border-radius: 8px;
            padding: .5rem 1.2rem; transition: background .2s; cursor: pointer;
            text-decoration: none;
        }
        .btn-info-custom:hover { background: #0284c7; color: #fff; }

        /* ── TABLE ─────────────────────────────── */
        .table-custom { width: 100%; border-collapse: collapse; }
        .table-custom thead th {
            font-size: .7rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: .04em; color: #64748b; padding: .6rem .75rem;
            border-bottom: 2px solid #e2e8f0; background: #f8fafc;
        }
        .table-custom tbody td {
            font-size: .78rem; padding: .55rem .75rem;
            border-bottom: 1px solid #f1f5f9; color: #334155;
            vertical-align: middle;
        }
        .table-custom tbody tr:hover { background: #f8fafc; }

        /* ── BREADCRUMB ───────────────────────── */
        .breadcrumb-custom {
            display: flex; align-items: center; gap: .4rem;
            font-size: .74rem; color: #94a3b8; margin-bottom: 1rem;
        }
        .breadcrumb-custom a { color: var(--blue-dark); text-decoration: none; font-weight: 500; }
        .breadcrumb-custom a:hover { text-decoration: underline; }
        .breadcrumb-custom .sep { color: #cbd5e1; }
        .breadcrumb-custom .current { color: #475569; font-weight: 600; }

        /* ── RESPONSIVE ───────────────────────── */
        @media (max-width: 1024px) { :root { --sidebar-w: 210px; } }
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

        @yield('styles')
    </style>
</head>
<body>

{{-- ═══════════ SIDEBAR ═══════════ --}}
<aside class="dash-sidebar" id="sidebar">
    <div class="sidebar-logo">
        <img src="{{ asset('img/104/logo_medicina_sf.png') }}" alt="Logo Medicina">
        <span>Facultad de<br>Medicina U.M.S.A.</span>
    </div>

    <nav class="sidebar-nav">
        <div class="section-label">Navegación</div>

        @role('admin')
            <a href="{{ route('dashboard') }}" class="{{ ($activePage ?? '') === 'panel' ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Panel de Control
            </a>
        @endrole

        @role('operador')
            <a href="{{ route('dashboard.operador') }}" class="{{ ($activePage ?? '') === 'panel' ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Panel de Control
            </a>
        @endrole

        @can('eventos.ver')
        <a href="{{ route('eventos.index') }}" class="{{ ($activePage ?? '') === 'certificados' ? 'active' : '' }}">
            <i class="bi bi-award-fill"></i> Certificados
        </a>
        @endcan

        @can('memorandums.ver')
        <a href="{{ route('memorandums.index') }}" class="{{ ($activePage ?? '') === 'memorandums' ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text-fill"></i> Memorándums
        </a>
        @endcan

        @role('admin')
        @can('usuarios.ver')
        <a href="{{ route('usuarios.index') }}" class="{{ ($activePage ?? '') === 'usuarios' ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Usuarios
        </a>
        @endcan
        <div class="section-label" style="margin-top:1rem;">Sistema</div>
        <a href="{{ route('reportes.index') }}" class="{{ ($activePage ?? '') === 'reportes' ? 'active' : '' }}">
            <i class="bi bi-file-earmark-bar-graph"></i> Reportes
        </a>
        @can('auditoria.ver')
        <a href="{{ route('auditoria.index') }}" class="{{ ($activePage ?? '') === 'auditoria' ? 'active' : '' }}">
            <i class="bi bi-shield-lock-fill"></i> Auditoría
        </a>
        @endcan
        {{-- Enlace de Configuración eliminado a petición del usuario --}}
        @endrole
    </nav>

    <div class="sidebar-user">
        <div class="name">Bienvenido, {{ Auth::user()->name }}</div>
        <div class="status"><span class="dot"></span> Conectado</div>
    </div>

    <div class="sidebar-logout">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"><i class="bi bi-box-arrow-left me-1"></i> Cerrar Sesión</button>
        </form>
    </div>
</aside>

{{-- ═══════════ HEADER ═══════════ --}}
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
            <input type="text" name="q" placeholder="Buscar certificados, memorándums…" value="{{ request('q') }}">
        </form>
    </div>

    <div class="profile">
        <div class="info d-none d-sm-block">
            <div class="uname">{{ Auth::user()->name }}</div>
            <div class="urole">{{ Auth::user()->roles->pluck('name')->map(fn($r) => ucfirst($r))->implode(', ') }}</div>
        </div>
        <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
    </div>
</header>

{{-- ═══════════ CONTENIDO ═══════════ --}}
<main class="dash-main">
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/js/app.js'])
    <link rel="icon" href="{{ asset('img/logo_medicina.png')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" defer></script>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton+SC&family=Roboto&family=Great+Vibes&display=swap" rel="stylesheet">
    <title>Certificados</title>
    <!-- CSS compacto global: escalado para 100% zoom -->
    <style>
        :root {
            --bs-body-font-size: 0.8125rem;
            --bs-body-line-height: 1.45;
        }
        body {
            font-size: 0.8125rem;
            background-color: #f8f9fa;
        }
        /* Navbar compacto */
        .navbar { min-height: 42px; }
        /* Compensar fixed-top navbar */
        body { padding-top: 46px; }
        /* Tablas compactas */
        .table > :not(caption) > * > * { padding: 0.28rem 0.5rem; font-size: 0.78rem; }
        .table thead th { font-size: 0.75rem; letter-spacing: 0.02em; }
        /* Botones compactos */
        .btn { padding: 0.22rem 0.65rem; font-size: 0.78rem; }
        .btn-sm { padding: 0.15rem 0.4rem; font-size: 0.72rem; }
        .btn-lg { padding: 0.35rem 0.9rem; font-size: 0.85rem; }
        /* Cards compactas */
        .card-header { padding: 0.45rem 0.85rem; font-size: 0.82rem; }
        .card-body   { padding: 0.75rem 0.85rem; }
        .card-footer { padding: 0.4rem 0.85rem; }
        /* Formularios compactos */
        .form-control, .form-select { padding: 0.22rem 0.5rem; font-size: 0.8rem; height: auto; }
        label, .form-label { font-size: 0.78rem; margin-bottom: 0.15rem; font-weight: 500; }
        .form-group { margin-bottom: 0.55rem; }
        .form-text, small, .text-muted { font-size: 0.72rem; }
        /* Headings reducidos */
        h1 { font-size: 1.35rem; }
        h2 { font-size: 1.2rem; }
        h3 { font-size: 1.05rem; }
        h4 { font-size: 0.95rem; }
        h5 { font-size: 0.88rem; }
        h6 { font-size: 0.82rem; }
        /* Alertas compactas */
        .alert { padding: 0.5rem 0.85rem; font-size: 0.8rem; }
        /* Modales compactos */
        .modal-header { padding: 0.55rem 1rem; }
        .modal-body   { padding: 0.7rem 1rem; }
        .modal-footer { padding: 0.45rem 1rem; }
        .modal-title  { font-size: 0.9rem; }
        /* Badges */
        .badge { font-size: 0.7rem; }
        /* Espaciado */
        .container { max-width: 1400px; }
        .mt-3, .my-3 { margin-top: 0.6rem !important; }
        .mb-3, .my-3 { margin-bottom: 0.6rem !important; }
        .mt-4 { margin-top: 0.85rem !important; }
        .mb-4 { margin-bottom: 0.85rem !important; }
        .py-3 { padding-top: 0.6rem !important; padding-bottom: 0.6rem !important; }
        .p-3  { padding: 0.6rem !important; }
        /* DataTables override */
        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_length select {
            font-size: 0.78rem; padding: 0.15rem 0.4rem;
        }
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate { font-size: 0.75rem; }
        .page-link { padding: 0.2rem 0.5rem; font-size: 0.75rem; }
    </style>

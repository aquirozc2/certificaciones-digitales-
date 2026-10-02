@auth
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top py-1">
    <div class="container-fluid px-3">
        <a class="navbar-brand fw-bold" href="{{ url('/') }}" style="font-size:0.9rem;">
            <img src="{{ asset('img/logo_medicina.png') }}" height="26" class="me-2" alt="">
            Certificaciones Digitales
        </a>
        <button class="navbar-toggler navbar-toggler-sm" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-0">
                @can('eventos.ver')
                <li class="nav-item">
                    <a class="nav-link py-1 {{ request()->is('eventos*') ? 'active' : '' }}"
                       href="{{ route('eventos.index') }}">
                        Certificados
                    </a>
                </li>
                @endcan
                @can('memorandums.ver')
                <li class="nav-item">
                    <a class="nav-link py-1 {{ request()->is('memorandums*') ? 'active' : '' }}"
                       href="{{ route('memorandums.index') }}">
                        Memorandums
                    </a>
                </li>
                @endcan
            </ul>
            <div class="d-flex align-items-center gap-3">
                <span class="text-light" style="font-size:0.78rem;">
                    {{ Auth::user()->name }}
                    <span class="text-secondary">· {{ Auth::user()->roles->pluck('name')->implode(', ') }}</span>
                </span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline mb-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm py-0 px-2" style="font-size:0.78rem;">
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
@endauth

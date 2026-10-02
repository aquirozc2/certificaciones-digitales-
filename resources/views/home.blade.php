<!DOCTYPE html>
<html lang="es">
<head>
    @include('partials.head')
</head>
<body>
    @include('partials.navbar')

    <div class="container py-3">
        <div class="mb-3">
            <h5 class="fw-bold text-dark mb-0">Panel Principal</h5>
            <small class="text-muted">Selecciona una sección para comenzar</small>
        </div>
        <div class="row g-3">
            @can('eventos.ver')
            <div class="col-md-4 col-sm-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-2">
                            <span style="font-size:1.6rem;">📜</span>
                            <span class="fw-bold ms-2" style="font-size:0.82rem;">CERTIFICADOS</span>
                        </div>
                        <p class="text-muted mb-3" style="font-size:0.78rem;">Generar certificados para cursos, talleres, seminarios u otros eventos.</p>
                        <a class="btn btn-primary btn-sm mt-auto" href="{{ route('eventos.index') }}">Ir a Certificados →</a>
                    </div>
                </div>
            </div>
            @endcan
            @can('memorandums.ver')
            <div class="col-md-4 col-sm-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-2">
                            <span style="font-size:1.6rem;">📋</span>
                            <span class="fw-bold ms-2" style="font-size:0.82rem;">MEMORANDUMS</span>
                        </div>
                        <p class="text-muted mb-3" style="font-size:0.78rem;">Generar memorandums para docentes, auxiliares y otros.</p>
                        <a class="btn btn-primary btn-sm mt-auto" href="{{ route('memorandums.index') }}">Ir a Memorandums →</a>
                    </div>
                </div>
            </div>
            @endcan
        </div>
    </div>

</body>
</html>
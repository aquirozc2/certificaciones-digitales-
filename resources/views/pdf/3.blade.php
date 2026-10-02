<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.head')
    <link rel="stylesheet" href="{{ asset('css/margin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fondo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/encabezado.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cuerpo.css') }}">
</head>
<style>
    .qr {
        position: absolute;
        bottom: 80px;
        left: 100px;
    }
    .firma{
    position: absolute;
    bottom: 80px; 
    left: 280px; 
    right: 100px;
    text-align: left;
    font-size: 13px; /*Tamaño de texto*/
    font-family: "Roboto Slab", serif;
    font-optical-sizing: auto;
    font-weight: <weight>;
    font-style: normal;
    line-height: 15px;
    color:rgb(23, 32, 42);
    }
</style>

<body>
    <!--Encabezado-->
    <div class="fondo">
        <img src="{{ asset('img/fondoH.png') }}" alt="fondo" width="1056px" height="816px">
    </div>
    <div class="logo_umsaH">
        <img src="{{ asset('img/umsa.png') }}" alt="logo umsa" width="80px">
    </div>
    <div class="logo_medicinaH">
        <img src="{{ asset('img/medicina.png') }}" alt="logo medicina" width="100px">
    </div>
    <div class="encabezado1">
        <h1>UNIVERSIDAD MAYOR DE SAN ANDRÉS</h1>
    </div>
    <div class="encabezado2">
        <h2>FACULTAD DE MEDICINA, ENFERMERÍA, NUTRICIÓN Y TECNOLOGÍA MÉDICA</h2>
    </div>
    <div class="encabezado3">
        <h2>CARRERA DE MEDICINA</h2>
    </div>
    <div class="encabezado4">
        <h2>DEPARTAMENTO FACULTATIVO DE MEDICINA Y SALUD MENTAL</h2>
    </div>

    <!--Datos-->
    <div class="confiere">Se confiere el Presente</div>
    <div class="correlativoH">FMENT/VICEDEC/CERT/DIG N° {{ $certificado->id }}/2025</div>
    <div class="certificadoH">
        <h1>CERTIFICADO</h1>
    </div>
    <div class="nombreH">
        A: {{ $certificado->grado }} {{ $certificado->nombre }} {{ $certificado->paterno }}
        {{ $certificado->materno }}
    </div>

    <!--Cuerpo-->
    <div class="texto1H">
        Por su participación en calidad de <b>{{ $certificado->tipo }}</b>
        en el <b>"Taller de Certificados Digitales"</b>
        Realizado en fecha 14 de mayo de 2025 en el Auditorio Ernesto Che Guevara - Piso 13 de la Facultad de Medicina<br>
        Carga horaria: 8 Horas académicas.
    </div>
    <div class="fechaH">La Paz, mayo de 2025</div>

    <!--Firma-->
    <div class="qr">
        <img src="data:image/png;base64,{{ base64_encode($qr) }}" height="150px" width="150px" alt="Código QR">
    </div>

    <div class="firma">
        Este documento es validado en la página de la ADSIB: <br>
        <em style="color: rgb(40, 116, 166)">https://validar.firmadigital.bo/</em> 
    </div>


    @include('partials.js')
</body>

</html>

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
        bottom: 100px;
        left: 50px;
    }
    .marilyn {
        position: absolute;
        bottom: 105px;
        left: 250px;
    }
    .firma{
    position: absolute;
    bottom: 70px; 
    left: 220px; 
    text-align: center;
    font-size: 13px; /*Tamaño de texto*/
    font-family: "Roboto Slab", serif;
    font-optical-sizing: auto;
    font-weight: <weight>;
    font-style: normal;
    line-height: 13px;
    color:rgb(23, 32, 42);
    }
    .vicedecana {
        position: absolute;
        bottom: 90px;
        right: 150px;
    }
    .sello {
        position: absolute;
        bottom: 200px;
        right: 30px;
    }
    .firma1{
    position: absolute;
    bottom: 90px; 
    left: 480px; 
    text-align: center;
    font-size: 13px; /*Tamaño de texto*/
    font-family: "Roboto Slab", serif;
    font-optical-sizing: auto;
    font-weight: <weight>;
    font-style: normal;
    line-height: 13px;
    color:rgb(23, 32, 42);
    }
    .logo_iinsad {
    position: absolute;
    top: 40px;
    right: 15px;
}
</style>

<body>
    <!--Encabezado-->
    <div class="fondo">
        <img src="{{ asset('img/fondos/iinsad.png') }}" alt="fondo" width="816px" height="1056px">
    </div>
    <div class="logo_umsa">
        <img src="{{ asset('img/umsa.png') }}" alt="logo umsa" width="80px">
    </div>
    <div class="logo_iinsad">
        <img src="{{ asset('img/logos/iinsad.jpeg') }}" alt="logo medicina" width="110px">
    </div>
    <div class="encabezado1">
        <h1>UNIVERSIDAD MAYOR DE SAN ANDRÉS</h1>
    </div>
    <div class="encabezado2">
        <h2>FACULTAD DE MEDICINA, ENFERMERÍA, NUTRICIÓN Y TECNOLOGÍA MÉDICA</h2>
    </div>
    <div class="encabezado3">
        <h2>INSTITUTO DE INVESTIGACIÓN EN SALUD Y DESARROLLO - IINSAD</h2>
    </div>

    <!--Datos-->
    <div class="confiere">Se confiere el Presente</div>
    <div class="correlativo">FMENT/VICEDEC/CERT/DIG N° {{ $certificado->id }}/2025</div>
    <div class="certificado">
        <h1>CERTIFICADO</h1>
    </div>
    <div class="nombre">
        A: {{ $certificado->grado }} {{ $certificado->nombre }} {{ $certificado->paterno }}
        {{ $certificado->materno }}
    </div>

    <!--Cuerpo-->
    @php
    use Illuminate\Support\Str;
@endphp
    <div class="texto1">
        Por su participación en calidad de: <br>
        <div style="text-align: center">
            <b>{{ Str::upper($certificado->tipo) }}</b>
        </div>
        A la <b>“VI Jornada Científica del IINSAD: Año del Bicentenario”</b>
        realizada de manera virtual con ponencias nacionales e internacionales, 
        los días 27 y 28 de noviembre de 2025, con una carga horaria de 10 horas académicas.
    </div>
    <div class="fecha">La Paz, diciembre de 2025</div>

    <!--Firma-->
    <div class="qr">
        <img src="data:image/png;base64,{{ base64_encode($qr) }}" height="150px" width="150px" alt="Código QR">
    </div>

    <div class="marilyn">
        <img src="{{ asset('img/firmas/ana.png') }}" alt="Firma Dra. Marilyn Aparicio Effen" width="200px">
    </div>
    <div class="firma">
        Dra. M.Sc. Ana Maria Aguilar Liendo<br>
        <b>DIRECTORA</b> <br>
        <b>Instituto de Investigación en Salud <br> y Desarrollo - IINSAD</b>
    </div>
    
    <div class="vicedecana">
        <img src="{{ asset('img/firmas/vicedecana.png') }}" alt="Firma Dra. Mónica C. Guzman Mendoza" width="150px">
    </div>
    <div class="firma1">
        Dra. Mónica C. Guzman Mendoza<br>
        <b>VICEDECANA</b> <br>
        <b>FACULTAD DE MEDICINA</b>
    </div>

    <div class="sello">
        <img src="{{ asset('img/sello_vicedecanato.png') }}" alt="Sello Vicedecanato" width="150px">
    </div>


    @include('partials.js')
</body>

</html>

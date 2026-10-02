<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.head')
    <link rel="stylesheet" href="{{ asset('css/margin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fondo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cuerpo.css') }}">
</head>
<style>
    .qr {
        position: absolute;
        bottom: 100px;
        left: 100px;
    }
    .firma{
    position: absolute;
    bottom: 100px; 
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
    .logo_umsa {
    position: absolute;
    top: 30px;
    left: 30px;
}

.logo_medicina {
    position: absolute;
    top: 40px;
    right: 30px;
}

    .encabezado1{
position: absolute;
top: 60px; 
left: 100px; 
right: 100px;
text-align: center;
font-size: 12px; /*Tamaño de texto*/
font-family: "Roboto Slab", serif;
font-optical-sizing: auto;
font-weight: <weight>;
font-style: normal;
color:rgb(26, 35, 126);
}

.encabezado2{
position: absolute;
top: 100px; 
left: 100px; 
right: 100px;
text-align: center;
font-size: 10px; /*Tamaño de texto*/
font-family: "Roboto Slab", serif;
font-optical-sizing: auto;
font-weight: <weight>;
font-style: normal;
color:rgb(26, 35, 126);
}

.encabezado3{
position: absolute;
top: 120px; 
left: 100px; 
right: 100px;
text-align: center;
font-size: 12px; /*Tamaño de texto*/
font-family: "Roboto Slab", serif;
font-optical-sizing: auto;
font-weight: <weight>;
font-style: normal;
color:rgb(26, 35, 126 );
}
.confiere{
    position: absolute;
    top: 180px; 
    left: 100px; 
    text-align: center;
    font-size: 20px; /*Tamaño de texto*/
    font-family: "Roboto Slab", serif;
    font-optical-sizing: auto;
    font-weight: <weight>;
    font-style: normal;
    color:rgb(23, 32, 42);
    }

    .certificado{
    position: absolute;
    top: 150px; 
    left: 100px; 
    right: 100px;
    text-align: center;
    font-size: 20px; /*Tamaño de texto*/
    font-family: "Roboto Slab", serif;
    font-optical-sizing: auto;
    font-weight: <weight>;
    font-style: normal;
    color:rgb(23, 32, 42);
    }
    .correlativo{
    position: absolute;
    top: 210px; 
    right: 100px; 
    left: 100px;
    text-align: center;
    font-size: 12px; /*Tamaño de texto*/
    font-family: "Roboto Slab", serif;
    font-optical-sizing: auto;
    font-weight: <weight>;
    font-style: normal;
    color:rgb(23, 32, 42);
    }
    .cab{
    position: absolute;
    top: 250px; 
    left: 100px; 
    right: 450px;
    text-align: center;
    font-size: 12px;
    line-height: 1;
    font-family: "Roboto Slab", serif;
    font-optical-sizing: auto;
    font-weight: <weight>;
    font-style: normal;
    color:rgb(23, 32, 42);
    }

    .nombre{
    position: absolute;
    top: 250px; 
    left: 425px; 
    right: 50px;
    text-align: left ;
    font-size: 12px;
    line-height: 1;
    font-family: "Roboto Slab", serif;
    font-optical-sizing: auto;
    font-weight: <weight>;
    font-style: normal;
    color:rgb(23, 32, 42);
    }
</style>

<body>
    <!--Encabezado-->
    <div class="fondo">
        <img src="{{ asset('img/FondoMem.png') }}" alt="fondo" width="816px" height="1056px">
    </div>
    <div class="logo_umsa">
        <img src="{{ asset('img/umsa.png') }}" alt="logo umsa" width="80px">
    </div>
    <div class="logo_medicina">
        <img src="{{ asset('img/medicina.png') }}" alt="logo medicina" width="100px">
    </div>
    <div class="encabezado1">
        <h1>UNIVERSIDAD MAYOR DE SAN ANDRÉS</h1>
    </div>
    <div class="encabezado2">
        <h2>FACULTAD DE MEDICINA, ENFERMERÍA, NUTRICIÓN Y TECNOLOGÍA MÉDICA</h2>
    </div>
    <div class="encabezado3">
        <h2>VICEDECANATO</h2>
    </div>

    <!--Datos-->
    <div class="certificado">
        <h1>MEMORANDUM</h1>
    </div>
    <div class="correlativo">FMENT/VICEDEC/MEM/TRIB.DOC.EST N° {{ $certificado->codigo }}/2025</div>

    <div class="cab">
        <b>UNIVERSIDAD MAYOR DE SAN ANDRÉS</b> <br>
        FACULTAD DE MEDICINA, ENFERMERÍA, <br>
        NUTRICIÓN Y TECNOLOGÍA MÉDICA <br>
        <b>VICEDECANATO</b>
    </div>

    <div class="nombre">
        Señor(a): <br>
        {{ $certificado->grado }} {{ $certificado->nombre }} {{ $certificado->paterno }} {{ $certificado->materno }} <br>
        <b>Docente - Facultad de Medicina, Enfermería, Nutrición y Tecnología Médica</b> <br>
        Presente.- <br><br>
        La Paz, {{ \Carbon\Carbon::now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
    </div>

    <!--Cuerpo-->
    <div class="texto1">
        Por su participación en calidad de <b>{{ $certificado->tipo }}</b>
        en el <b>"Taller de Certificados Digitales"</b>
        Realizado en fecha 14 de mayo de 2025 en el Auditorio Ernesto Che Guevara - Piso 13 de la Facultad de Medicina<br>
        Carga horaria: 8 Horas académicas.
    </div>
    <div class="fecha">La Paz, mayo de 2025</div>

    <!--Firma-->
    <div class="qr">
        <img src="data:image/png;base64,{{ base64_encode($qr) }}" height="150px" width="150px" alt="Código QR">
    </div>

    <div class="firma">
        Este documento es validado en la página de la ADSIB:
        <em style="color: rgb(40, 116, 166)">https://validar.firmadigital.bo/</em> 
    </div>


    @include('partials.js')
</body>

</html>

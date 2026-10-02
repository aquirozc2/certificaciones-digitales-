<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.head')
    <link rel="stylesheet" href="{{ asset('css/margin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fondo.css') }}">
</head>
<style>
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

.logo_umsaH {
    position: absolute;
    top: 30px;
    left: 80px;
}

.logo_medicinaH {
    position: absolute;
    top: 40px;
    right: 80px;
}
.encabezado1{
position: absolute;
top: 40px; 
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
top: 75px; 
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
top: 96px; 
left: 100px; 
right: 100px;
text-align: center;
font-size: 11px; /*Tamaño de texto*/
font-family: "Roboto Slab", serif;
font-optical-sizing: auto;
font-weight: <weight>;
font-style: normal;
color:rgb(26, 35, 126);
}

.encabezado4{
position: absolute;
top: 120px; 
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

.encabezado5{
    position: absolute;
    top: 140px; 
    left: 100px; 
    right: 100px;
    text-align: center;
    font-size: 10px; /*Tamaño de texto*/
    font-family: "Roboto Slab", serif;
    font-optical-sizing: auto;
    font-weight: <weight>;
    font-style: normal;
    color:rgb(231, 76, 60 );
}


    .correlativo{
    position: absolute;
    top: 160px; 
    left: 100px; 
    text-align: center;
    font-size: 15px; /*Tamaño de texto*/
    font-family: "Roboto Slab", serif;
    font-optical-sizing: auto;
    font-weight: <weight>;
    font-style: normal;
    color:rgb(23, 32, 42);
    }
    .fecha_actual{
    position: absolute;
    top: 180px; 
    left: 100px; 
    text-align: center;
    font-size: 15px; /*Tamaño de texto*/
    font-family: "Roboto Slab", serif;
    font-optical-sizing: auto;
    font-weight: <weight>;
    font-style: normal;
    color:rgb(23, 32, 42);
    }
    .correlativoH{
    position: absolute;
    top: 150px; 
    right: 50px; 
    text-align: center;
    font-size: 15px; /*Tamaño de texto*/
    font-family: "Roboto Slab", serif;
    font-optical-sizing: auto;
    font-weight: <weight>;
    font-style: normal;
    color:rgb(23, 32, 42);
    }
    .certificado{
    position: absolute;
    top: 220px; 
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
    .certificadoH{
    position: absolute;
    top: 200px; 
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
    .nombre{
    position: absolute;
    top: 280px; 
    left: 70px; 
    right: 70px;
    text-align: center;
    font-size: 43px; /*Tamaño de texto*/
    font-family: "Great Vibes", cursive;
    font-weight: 400;
    font-style: normal;
    color:rgb(23, 32, 42);
    }
    .nombreH{
    position: absolute;
    top: 260px; 
    left: 70px; 
    right: 70px;
    text-align: center;
    font-size: 43px; /*Tamaño de texto*/
    font-family: "Great Vibes", cursive;
    font-weight: 400;
    font-style: normal;
    color:rgb(23, 32, 42);
    }
    .texto1{
    position: absolute;
    top: 300px; 
    left: 100px; 
    right: 100px;
    text-align: justify;
    font-size: 20px;
    line-height: 1;
    font-family: "Roboto Slab", serif;
    font-optical-sizing: auto;
    font-weight: <weight>;
    font-style: normal;
    color:rgb(23, 32, 42);
    }
    .texto2{
    position: absolute;
    top: 400px; 
    left: 100px; 
    right: 100px;
    text-align: justify;
    font-size: 20px;
    line-height: 1;
    font-family: "Roboto Slab", serif;
    font-optical-sizing: auto;
    font-weight: <weight>;
    font-style: normal;
    color:rgb(23, 32, 42);
    }
    .texto3{
    position: absolute;
    top: 440px; 
    left: 100px; 
    right: 100px;
    text-align: justify;
    font-size: 20px;
    line-height: 1;
    font-family: "Roboto Slab", serif;
    font-optical-sizing: auto;
    font-weight: <weight>;
    font-style: normal;
    color:rgb(23, 32, 42);
    }


    .fecha {
    position: absolute;
    right:100px;
    bottom:360px;
    text-align: justify;
    font-size: 20px; /*Tamaño de texto*/
    font-family: "Roboto Slab", serif;
    font-optical-sizing: auto;
    font-weight: <weight>;
    font-style: normal;
    color:rgb(23, 32, 42);
}
    .fechaH {
    position: absolute;
    right:100px;
    bottom:280px;
    text-align: justify;
    font-size: 20px; /*Tamaño de texto*/
    font-family: "Roboto Slab", serif;
    font-optical-sizing: auto;
    font-weight: <weight>;
    font-style: normal;
    color:rgb(23, 32, 42);
}

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

    .vicedecana {
    position: absolute;
    bottom: 150px;
    right: 100px;
    left: 100px;
    text-align: center;
    }
    .firma1{
    position: absolute;
    bottom: 150px;
    right: 100px; 
    left: 100px; 
    text-align: center;
    font-size: 13px; /*Tamaño de texto*/
    font-family: "Roboto Slab", serif;
    font-optical-sizing: auto;
    font-weight: <weight>;
    font-style: normal;
    line-height: 13px;
    color:rgb(23, 32, 42);
    }
    .sello {
        position: absolute;
        bottom: 200px;
        right: 80px;
    }

</style>

<body>
    <!--Encabezado-->
    <div class="fondo">
        <img src="{{ asset('img/fondoV.png') }}" alt="fondo" width="816px" height="1056px">
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
        <h2>PRIMERA FACULTAD ACREDITADA Y REACREDITADA</h2>
    </div>
    <div class="encabezado4">
        <h1>VICEDECANATO</h1>
    </div>

    <!--Datos-->
    <div class="correlativo">FAC.MED.VICED.CERT.DIG N° {{ $certificado->id }}/2026</div>
    <div class="fecha_actual">La Paz, {{ \Carbon\Carbon::now()->locale('es')->translatedFormat('d \\d\\e F \\d\\e Y') }}</div>
    <div class="certificado">
        <h1>CERTIFICADO</h1>
    </div>

    <!--Cuerpo-->
    <div class="texto1">
        <b>La Suscrita Vicedecana de la Facultad de Medicina, Enfermería, Nutrición y Tecnología Médica
            dependiente de la Universidad Mayor de San Andrés. </b>
    </div>
    <div class="texto2">
            CERTIFICA:
    </div>
    <div class="texto3">
        Que, la <b>{{ $certificado->grado }} {{ $certificado->nombre }} {{ $certificado->paterno }} {{ $certificado->materno }}</b>, 
        de la Facultad de Medicina, Enfermería, Nutrición y Tecnología Médica, cumplio las funciones en la <b>{{ $certificado->tipo}}</b> en la
        <b>Convocatoria Nº 04/2025 para Concurso de Méritos y Examen de Competencia para Auxiliares de Docencia, Investigación e Interacción Social Gestión 2025</b> 
        de las <b>Cátedras de Microbiología, Fisiopatología, y Parasitología</b> del Departamento Facultativo de Patología. 
        <br><br>
        Es cuanto tengo a bien certificar para fines consiguientes.
    </div>

    <!--Firma-->
    <div class="qr">
        <img src="data:image/png;base64,{{ base64_encode($qr) }}" height="150px" width="150px" alt="Código QR">
    </div>

    <div class="firma">
        Este documento es validado en la página de la ADSIB:
        <em style="color: rgb(40, 116, 166)">https://validar.firmadigital.bo/</em> 
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

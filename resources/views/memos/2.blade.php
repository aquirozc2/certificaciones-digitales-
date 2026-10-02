<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.head')
    <link rel="stylesheet" href="{{ asset('css/margin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fondo.css') }}">

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
    .texto1{
    position: absolute;
    top: 400px; 
    left: 80px; 
    right: 80px;
    text-align: justify;
    font-size: 16px;
    line-height: 1.3;
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
        right: 100px;
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
    <div class="correlativo">FMENT/VICEDEC/MEM N° {{ $persona->id }}/2026</div>

    <div class="cab">
        <b>UNIVERSIDAD MAYOR DE SAN ANDRÉS</b> <br>
        FACULTAD DE MEDICINA, ENFERMERÍA, <br>
        NUTRICIÓN Y TECNOLOGÍA MÉDICA <br>
        <b>VICEDECANATO</b>
    </div>

    <div class="nombre">
        Señor(a): <br>
        {{ $persona->grado }} {{ $persona->nombre }} {{ $persona->paterno }} {{ $persona->materno }} <br>
        <b> {{ $persona->tipo }} - Facultad de Medicina, Enfermería, Nutrición y Tecnología Médica</b> <br>
        Presente.- <br><br>
        La Paz, {{ \Carbon\Carbon::now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
    </div>

    <!--Cuerpo-->
    <div class="texto1">
        De mi consideración:<br>
        En cumplimiento a la <b>{{ $memorandum->resolucion }}</b>
        de fecha {{ \Carbon\Carbon::parse($memorandum->fecha_resolucion)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }},
        de la Facultad de Medicina, Enfermería, Nutrición y Tecnología Médica,
        comunico a usted que ha sido designado(a) para conformar el <b>{{ $memorandum->detalle }}</b>
        para la <b>{{ $memorandum->evento }}</b> Salud Pública I Interacción Social del {{ $memorandum->departamento_mension }}.
        <br>
        Deseandole éxitos en las funciones asignadas, saludo a usted muy cordialmente.
        <br>
        Atentamente,

    </div>

    <!--Firma-->
    <div class="qr">
        <img src="data:image/png;base64,{{ base64_encode($qr) }}" height="150px" width="150px" alt="Código QR">
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

    <div class="firma">
        Este documento es validado en la página de la ADSIB:
        <em style="color: rgb(40, 116, 166)">https://validar.firmadigital.bo/</em> 
    </div>


    @include('partials.js')

    @include('pdf.partials.generated-by')
</body>

</html>

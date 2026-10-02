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
    .certificadoA{
    position: absolute;
    top: 230px; 
    left: 100px; 
    right: 100px;
    text-align: left;
    font-size: 20px; /*Tamaño de texto*/
    font-family: "Roboto Slab", serif;
    font-optical-sizing: auto;
    font-weight: <weight>;
    font-style: normal;
    color:rgb(23, 32, 42);
    }
        .fechaA {
    position: absolute;
    right:100px;
    bottom:250px;
    text-align: justify;
    font-size: 20px; /*Tamaño de texto*/
    font-family: "Roboto Slab", serif;
    font-optical-sizing: auto;
    font-weight: <weight>;
    font-style: normal;
    color:rgb(23, 32, 42);
}
    .jordan {
    position: absolute;
    bottom: 100px;
    left: 300px;
}

.firma{
    position: absolute;
    bottom: 80px; 
    left: 300px; 
    text-align: center;
    font-size: 13px; /*Tamaño de texto*/
    font-family: "Roboto Slab", serif;
    font-optical-sizing: auto;
    font-weight: <weight>;
    font-style: normal;
    line-height: 13px;
    color:rgb(23, 32, 42);
    }

        .firma1{
    position: absolute;
    bottom: 80px; 
    right: 200px; 
    text-align: center;
    font-size: 13px; /*Tamaño de texto*/
    font-family: "Roboto Slab", serif;
    font-optical-sizing: auto;
    font-weight: <weight>;
    font-style: normal;
    line-height: 13px;
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
        <h2>CARRERA DE NUTRICIÓN Y DIETÉTICA</h2>
    </div>

    <!--Datos-->
    <div class="correlativoH">FMENT/VICEDEC/CERT/DIG N° {{ $certificado->id }}/2025</div>
    <div class="certificadoA">
        <h3>CERTIFICA:</h3>
    </div>
    <div class="nombreH">
        A: {{ $certificado->grado }} {{ $certificado->nombre }} {{ $certificado->paterno }}
        {{ $certificado->materno }}
    </div>

    <!--Cuerpo-->
    <div class="texto1H">
        Que, Participó en la <b> Feria SOBRE LACTANCIA MATERNA</b> dirigido a la población en general, madres gestantes y en etapa de lactancia,  
        realizada por la <b> “Brigada Universitaria Promoviendo la Lactancia Materna”, </b>    
        el 5 de septiembre  2025. En el Atrio de la UMSA Proyecto de Interacción Social Multidisciplinario aprobado mediante   
        Resolución del HCF   No. 0206/ 2015.
    </div>  
    <div class="fechaA">La Paz, septiembre de 2025</div>

    <!--Firma-->
    <div class="qr">
        <img src="data:image/png;base64,{{ base64_encode($qr) }}" height="150px" width="150px" alt="Código QR">
    </div>
    


    <div class="firma">
        M.Sc. Magdalena Jordán de Guzmán<br>
        <b>COORDINADORA GENERAL</b> <br>
        <b>DOCENTE CARRERA DE NUTRICIÓN Y DIETÉTICA</b>
    </div>

    <div class="firma1">
        Dra. Mónica C. Guzman Mendoza<br>
        <b>VICEDECANA</b> <br>
        <b>FACULTAD DE MEDICINA</b>
    </div>

    @include('partials.js')
</body>

</html>

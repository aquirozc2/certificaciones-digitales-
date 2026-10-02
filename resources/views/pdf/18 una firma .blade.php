<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        /* =========================================
           1. FUENTES BASE64
           ========================================= */
        @font-face {
            font-family: 'Roboto';
            src: url('{{ $fontRobotoRegular }}') format('truetype');
            font-weight: normal; font-style: normal;
        }
        @font-face {
            font-family: 'Roboto Slab';
            src: url('{{ $fontRobotoSlab }}') format('truetype');
            font-weight: normal; font-style: normal;
        }
        @font-face {
            font-family: 'Roboto Slab';
            src: url('{{ $fontRobotoSlabMedium }}') format('truetype');
            font-weight: 500; font-style: normal;
        }
        @font-face {
            font-family: 'Roboto Slab';
            src: url('{{ $fontRobotoSlabBold }}') format('truetype');
            font-weight: 700; font-style: normal;
        }
        @font-face {
            font-family: 'Great Vibes';
            src: url('{{ $fontGreatVibes }}') format('truetype');
            font-weight: normal; font-style: normal;
        }

        /* =========================================
           2. ESTILOS GENERALES
           ========================================= */
        @page { margin: 0px; }
        body {
            margin: 0; padding: 0;
            width: 816px; height: 1056px;
            font-family: 'Roboto Slab', serif;
            color: #000;
        }

        .fondo { position: absolute; top: 0; left: 0; z-index: -1000; }
        
        /* Logos */
        .logo_umsa { position: absolute; top: 30px; left: 40px; }
        .logo_medicina { position: absolute; top: 30px; right: 40px; }

        /* =========================================
           3. ENCABEZADO OFICIAL (Tipo Vicedecanato)
           ========================================= */
        .encabezado-bloque {
            position: absolute; top: 40px; left: 100px; right: 100px;
            text-align: center;
            line-height: 1.05;
        }
        .linea1 { font-size: 22px; font-weight: 500; color: rgb(26, 35, 126); margin-bottom: 2px; }
        .linea2 { font-size: 14px; font-weight: 500; color: rgb(26, 35, 126); margin-bottom: 2px; }
        .linea3 { font-size: 14px; font-weight: 500; color: rgb(26, 35, 126); margin-bottom: 3px; }
        .linea4 { font-size: 16px; font-weight: 500; color: rgb(26, 35, 126); margin-top: 4px; } /* VICEDECANATO */

        /* =========================================
           4. DATOS DE CONTROL (Restaurados)
           ========================================= */
        .datos-control {
            position: absolute; top: 185px; left: 100px;
            font-size: 14px;
            font-weight: 400;
            color: #000;
            line-height: 1;
            text-align: left;
        }

        /* =========================================
           5. CUERPO DEL CERTIFICADO (Tenor IINSAD)
           ========================================= */
        
        /* Etiqueta gris */
        .etiqueta-confiere {
            position: absolute; top: 255px; left: 88px;
            background-color: transparent;
            padding: 4px 12px;
            font-size: 16px;
            font-weight: normal;
        }

        .titulo-certificado {
            position: absolute; top: 290px; left: 0; right: 0;
            text-align: center; font-size: 30px; font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* Bloque Nombre */
        .nombre-container {
            position: absolute; top: 350px; left: 50px; right: 50px;
            text-align: center;
        }
        .nombre-prefijo { font-size: 18px; display: inline-block; margin-right: 5px; }
        .nombre-persona { 
            font-weight: normal; 
            font-family: 'Great Vibes', cursive; 
            font-size: 35px; 
        }

        /* Texto de institución debajo del nombre */
        .texto-institucion {
            position: absolute; top: 410px; left: 0; right: 0;
            text-align: center; font-size: 16px;
        }

        /* "Por su participación..." */
        .texto-calidad {
            position: absolute; top: 450px; left: 100px;
            text-align: left; font-size: 16px;
        }

        /* ROL (DOCENTE, EXPOSITORA, ETC) */
        .rol-participante {
            position: absolute; top: 480px; left: 0; right: 0;
            text-align: center; font-size: 28px; font-weight: bold;
            text-transform: uppercase;
        }

        /* Párrafo descriptivo */
        .descripcion-evento {
            position: absolute; top: 540px; left: 100px; right: 100px;
            text-align: justify; font-size: 17px;
            line-height: 1.5;
        }

        /* =========================================
           6. PIE DE PÁGINA Y FIRMAS (Restaurados)
           ========================================= */
        
        /* Bloque Firma Vicedecana */
        .bloque-firma {
            position: absolute; bottom: 160px; left: 0; right: 0;
            text-align: center;
        }
        .nombre-autoridad {
            font-size: 13px;
            font-weight: 400;
            font-family: 'Roboto Slab', serif;
            line-height: 1;
            margin-top: -55px;
        }
        .cargo-autoridad {
            font-size: 13px; font-weight: bold; margin-top: -80px;
        }

        /* Sello (A la derecha de la firma) */
        .sello {
            position: absolute; bottom: 130px; right: 120px;
        }

        /* QR y Validación */
        .qr { position: absolute; bottom: 90px; left: 80px; }

        .firma-digital-texto {
            position: absolute; bottom: 90px; left: 230px; right: 30px;
            font-size: 10px;
            line-height: 1.2;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="fondo"><img src="{{ $fondoBase64 }}" width="816" height="1056"></div>

    <div class="logo_umsa"><img src="{{ $umrsaBase64 }}" width="85"></div>
    <div class="logo_medicina"><img src="{{ $medicinaBase64 }}" width="95"></div>

    <div class="encabezado-bloque">
        <div class="linea1">UNIVERSIDAD MAYOR DE SAN ANDRÉS</div>
        <div class="linea2">FACULTAD DE MEDICINA, ENFERMERÍA, NUTRICIÓN Y TECNOLOGÍA MÉDICA</div>
        <div class="linea3">INSTITUTO DE INVESTIGACIÓN EN SALUD Y DESARROLLO IINSAD</div>
        <!--
        <div class="linea4">VICEDECANATO</div>
        -->
    </div>
    <br><br><br>
    <div class="datos-control">
        FAC.MED.VICED.CERT.DIG N° {{ $certificado->id }}/2026<br>
        La Paz, {{ \Carbon\Carbon::now()->locale('es')->translatedFormat('d \\d\\e F \\d\\e Y') }}
    </div>

    <div class="etiqueta-confiere">Se confiere el presente:</div>

    <div class="titulo-certificado">CERTIFICADO</div>

    <div class="nombre-container">
        <span class="nombre-prefijo">A:</span>
        <span class="nombre-persona">{{ $certificado->grado }} {{ $certificado->nombre }} {{ $certificado->paterno }} {{ $certificado->materno }}</span>
    </div>
    <!--
    <div class="texto-institucion">Universidad Pontificia Católica de Chile</div>
-->
    <div class="texto-calidad">Por su participación en calidad de:</div>

    <div class="rol-participante">
        {{ $certificado->tipo ?? 'PARTICIPANTE' }}
    </div>

    <div class="descripcion-evento">
        En la <strong><b>“{{ $evento->evento }}”</b></strong>, 
        en la presentación de investigaciones en las Unidades de IINSAD,
        realizada de manera virtual con ponencias nacionales e internacionales,
        los días {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d') }} y {{ \Carbon\Carbon::parse($evento->fecha_fin)->translatedFormat('d \d\e F \d\e Y') }}, 
        con una carga horaria de {{ $evento->horas ?? '10' }} horas académicas.
        <br>La Paz, febrero de 2026.
    </div>

    <div class="bloque-firma">
        <img src="{{ $firmaBase64 }}" width="150"><br>
        <div class="nombre-autoridad">Dra. Mónica C. Guzman Mendoza<br>VICEDECANA<br>FACULTAD DE MEDICINA</div>
    </div>

    <div class="sello">
        <img src="{{ $selloBase64 }}" width="140">
    </div>

    <div class="qr">
        <img src="data:image/png;base64,{{ base64_encode($qr) }}" width="140" height="140">
    </div>

    <div class="firma-digital-texto">
        Este documento es validado en la página de la ADSIB:<br>
        <span style="color: #1a5276;">https://validar.firmadigital.bo/</span><br>
    </div>

</body>
</html>
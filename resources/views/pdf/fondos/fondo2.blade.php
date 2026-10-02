<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        /* Fuentes */
        @font-face {
            font-family: 'Roboto';
            src: url('{{ $fontRobotoRegular }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'Roboto';
            src: url('{{ $fontRobotoRegular }}') format('truetype');
            font-weight: bold;
            font-style: normal;
        }
        @font-face {
            font-family: 'Roboto Slab';
            src: url('{{ $fontRobotoSlab }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'Roboto Slab';
            src: url('{{ $fontRobotoSlabBold }}') format('truetype');
            font-weight: bold;
            font-style: normal;
        }
        @font-face {
            font-family: 'Great Vibes';
            src: url('{{ $fontGreatVibes }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @page { margin: 0px; }

        html {
            background: #eef1f6;
        }

        @if(($modoPreview ?? false) === true)
        html {
            padding: 12px;
            overflow: auto;
        }
        @endif

        body {
            margin: 0;
            padding: 0;
            width: 816px;
            height: 1056px;
            font-family: 'Roboto Slab', serif;
            color: #000;
            position: relative;
        }

        @if(($modoPreview ?? false) === true)
        body {
            margin: 0 auto 12px;
            background: #fff;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.18);
            border: 1px solid #cbd5e1;
            outline: 1px dashed #94a3b8;
            outline-offset: -8px;
        }

        body::before {
            content: 'Tamaño carta';
            position: absolute;
            top: 10px;
            right: 10px;
            background: #0f172a;
            color: #fff;
            font-family: 'Roboto', sans-serif;
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 6px;
            letter-spacing: 0.01em;
            z-index: 3;
        }
        @endif

        .fondo {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: -1;
        }

        @if(($modoPreview ?? false) === true)
        .fondo { z-index: 0; }
        @endif

        .encabezado {
            position: absolute;
            top: 55px;
            left: 140px;
            right: 140px;
            text-align: center;
            font-family: 'Roboto Slab', serif;
            line-height: 1.0;
            color: #0d1b6e;
            font-weight: bold;
        }

        .encabezado p {
            font-size: 14px;
            color: #0d1b6e !important;
            font-weight: bold;
            margin: 1px 0;
            line-height: 1.0;
        }

        .encabezado p:first-child { font-size: 20px; margin-bottom: 2px; }

        .encabezado span, .encabezado strong, .encabezado em, .encabezado b {
            color: #0d1b6e !important;
        }

        .codigo-cert {
            position: absolute;
            top: 180px;
            left: 85px;
            font-family: 'Roboto Slab', serif;
            font-size: 13px;
            color: #000;
            line-height: 1.0;
        }

        .tenor {
            position: absolute;
            top: 240px;
            left: 85px;
            right: 85px;
            font-family: 'Roboto Slab', serif;
            font-size: 18px;
            line-height: 1.3;
            color: #000;
        }

        .tenor p {
            margin: 0; padding: 0;
            text-align: justify;
            line-height: 1.3;
        }

        .tenor p:empty, .tenor p br {
            line-height: 0.4;
            font-size: 6px;
        }

        .tenor strong { font-weight: bold; }
        .tenor em { font-style: italic; }

        .qr-code {
            position: absolute;
            bottom: 129px;
            left: 81px;
            width: 155px;
            height: 155px;
        }

        .ql-size-large { font-size: 20px; }
        .ql-size-huge  { font-size: 24px; }

        .ql-align-center, .tenor p.ql-align-center { text-align: center !important; }
        .ql-align-right,  .tenor p.ql-align-right  { text-align: right  !important; }
        .ql-align-justify,.tenor p.ql-align-justify { text-align: justify!important; }
        .tenor p:not([class*="ql-align"]) { text-align: inherit; }
    </style>
</head>
<body>
    <img src="{{ $fondoBase64 }}" class="fondo" alt="Fondo">

    <div class="encabezado">{!! $encabezadoFinal !!}</div>

    <div class="codigo-cert">
        <div>FAC.MED.VICED.CERT.DIG N° {{ $certificado->id }}/{{ date('Y') }}</div>
        <div>La Paz, {{ \Carbon\Carbon::now()->locale('es')->translatedFormat('d \de F \de Y') }}</div>
    </div>

    <div class="tenor">{!! $tenorFinal !!}</div>

    <div class="qr-code">
        <img src="{{ $qr }}" width="155" height="155" alt="QR">
    </div>

    @include('pdf.partials.generated-by')
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @font-face {
            font-family: 'Roboto';
            src: url('{{ $fontRobotoRegular }}') format('truetype');
            font-weight: normal; font-style: normal;
        }
        @font-face {
            font-family: 'Roboto';
            src: url('{{ $fontRobotoRegular }}') format('truetype');
            font-weight: bold; font-style: normal;
        }
        @font-face {
            font-family: 'Roboto Slab';
            src: url('{{ $fontRobotoSlab }}') format('truetype');
            font-weight: normal; font-style: normal;
        }
        @font-face {
            font-family: 'Roboto Slab';
            src: url('{{ $fontRobotoSlabBold }}') format('truetype');
            font-weight: bold; font-style: normal;
        }
        @font-face {
            font-family: 'Great Vibes';
            src: url('{{ $fontGreatVibes }}') format('truetype');
            font-weight: normal; font-style: normal;
        }
        @page { margin: 0px; }
        html {
            background: #eef1f6;
        }
        body {
            margin: 0; padding: 0;
            width: 816px; height: 1056px;
            font-family: 'Roboto Slab', serif;
            color: #000; position: relative;
        }

        @if(($modoPreview ?? false) === true)
        html {
            padding: 12px;
            overflow: auto;
        }

        body {
            margin: 0 auto 12px;
            background: #fff;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.18);
            border: 1px solid #cbd5e1;
            outline: 1px dashed #94a3b8;
            outline-offset: -8px;
        }

        body::before {
            content: 'Tamaño carta: 216 x 279 mm';
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
            position: absolute; top: 0; left: 0;
            width: 100%; height: 100%; z-index: 0;
        }
        .encabezado {
            position: absolute;
            top: 55px; left: 120px; right: 120px;
            text-align: center;
            font-family: 'Roboto Slab', serif;
            color: #1a237e; line-height: 1.2;
        }
        .encabezado p {
            font-size: 15px; margin: 1px 0; line-height: 1.0;
            font-family: 'Roboto Slab', serif;
            color: #1a237e !important; font-weight: bold;
        }
        .encabezado p:first-child { font-size: 22px; }
        .encabezado span, .encabezado strong, .encabezado b { color: #1a237e !important; }
        .certificado {
            position: absolute; top: 150px; left: 100px; right: 100px;
            text-align: center; font-size: 32px; font-weight: bold;
            font-family: 'Roboto Slab', serif; color: #000;
        }
        .correlativo {
            position: absolute; top: 195px; left: 100px; right: 100px;
            text-align: center; font-size: 12px;
            font-family: 'Roboto Slab', serif; color: #000;
        }
        .cab {
            position: absolute;
            top: 250px; left: 100px; right: 450px;
            font-size: 11px; line-height: 1.0;
            font-family: 'Roboto Slab', serif; color: #000;
        }
        .cab p { margin: 0; padding: 0; line-height: 1.0; font-size: 11px; }
        .nombre {
            position: absolute;
            top: 235px; left: 425px; right: 50px;
            font-size: 12px; line-height: 1.1;
            font-family: 'Roboto Slab', serif; color: #000;
        }
        .nombre p { margin: 0; padding: 0; line-height: 1.1; }
        .nombre p:empty, .nombre p br { line-height: 0.4; font-size: 6px; }
        .texto1 {
            position: absolute;
            top: 390px; left: 80px; right: 80px;
            font-size: 13px; line-height: 1.5;
            font-family: 'Roboto Slab', serif; color: #000;
            text-align: justify;
        }
        .texto1 p { margin: 0; padding: 0; text-align: justify; line-height: 1.2; }
        .texto1 p:empty { line-height: 0.4; font-size: 6px; }
        .texto1 strong { font-weight: bold; }
        .texto1 em { font-style: italic; }
        .ql-align-center, .cab p.ql-align-center,
        .nombre p.ql-align-center, .texto1 p.ql-align-center { text-align: center !important; }
        .ql-align-right,  .cab p.ql-align-right,
        .nombre p.ql-align-right,  .texto1 p.ql-align-right  { text-align: right  !important; }
        .ql-align-justify, .texto1 p.ql-align-justify         { text-align: justify!important; }
        .ql-size-large { font-size: 17px; }
        .ql-size-huge  { font-size: 21px; }
        .qr {
            position: absolute; bottom: 127px; left: 80px;
            width: 158px; height: 158px;
        }
    </style>
</head>
<body>
    <img src="{{ $fondoBase64 }}" class="fondo" alt="Fondo">

    <div class="encabezado">{!! $encabezadoFinal !!}</div>

    <div class="certificado">MEMORANDUM</div>
    <div class="correlativo">FMENT/VICEDEC/MEM N° {{ $persona->id }}/{{ date('Y') }}</div>

    <div class="cab">{!! $cabFinal !!}</div>
    <div class="nombre">{!! $destinatarioFinal !!}</div>

    <div class="texto1">{!! $tenorFinal !!}</div>

    <div class="qr">
        <img src="{{ $qr }}" width="158" height="158" alt="QR">
    </div>

    @include('pdf.partials.generated-by')
</body>
</html>
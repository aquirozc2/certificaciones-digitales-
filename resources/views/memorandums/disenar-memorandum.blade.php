<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diseñar Memorandum - {{ $memorandum->evento }}</title>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Roboto+Slab&family=Great+Vibes&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f1f5f9;
            padding: 12px;
            padding-top: 68px;
        }

        /* ── Top bar ── */
        .top-bar {
            position: fixed; top: 0; left: 0; right: 0; height: 52px; z-index: 9999;
            background: linear-gradient(90deg, #1e3a8a 0%, #1d4ed8 100%);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 1.2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,.2);
        }
        .top-bar .brand { display: flex; align-items: center; gap: .6rem; }
        .top-bar .brand img { height: 28px; border-radius: 50%; background: #fff; padding: 2px; }
        .top-bar .brand-text { font-size: .78rem; font-weight: 700; color: #fff; }
        .top-bar .brand-sub { font-size: .65rem; color: rgba(255,255,255,.65); }
        .top-bar .back-btn {
            display: inline-flex; align-items: center; gap: .4rem;
            background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
            color: #fff; padding: .35rem .85rem; border-radius: 7px;
            font-size: .75rem; font-weight: 600; text-decoration: none;
            transition: background .2s;
        }
        .top-bar .back-btn:hover { background: rgba(255,255,255,.22); }

        .container { max-width: 1600px; margin: 0 auto; }

        .header {
            background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 100%);
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(30,58,138,.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            color: #fff;
        }

        .header h1 { color: #fff; font-size: 1.1rem; }
        .header .sub { color: rgba(255,255,255,.75); font-size: 12px; margin-top: 2px; }

        .main-grid {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 12px;
            margin-bottom: 12px;
        }

        .editor-panel {
            background: white;
            border-radius: 8px;
            padding: 14px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .preview-panel {
            background: white;
            border-radius: 8px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            position: sticky;
            top: 8px;
            max-height: calc(100vh - 60px);
            overflow-y: auto;
        }

        .section-title {
            font-size: 0.88rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 2px solid #667eea;
        }

        .editor-wrapper { margin-bottom: 10px; }

        .editor-label {
            font-weight: 600;
            color: #444;
            margin-bottom: 4px;
            display: block;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .editor-label span {
            font-weight: normal;
            color: #999;
            font-size: 10px;
            text-transform: none;
            letter-spacing: 0;
        }

        .variables-bar {
            background: #f0f2ff;
            padding: 4px 8px;
            border-radius: 5px;
            margin-bottom: 5px;
            border: 1px solid #d0d5f5;
            display: flex;
            flex-wrap: wrap;
            gap: 3px;
            align-items: center;
        }

        .variables-bar .var-label {
            font-size: 10px;
            color: #666;
            font-weight: 600;
            margin-right: 2px;
            white-space: nowrap;
        }

        .var-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 2px 7px;
            border-radius: 10px;
            font-size: 10px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .var-btn:hover { background: #5a6fd6; }

        .ql-container { min-height: 60px; }
        .ql-container.tall { min-height: 100px; }

        /* Tamaños Quill */
        .ql-snow .ql-size .ql-picker-item[data-value="13px"]::before { content: '13px' !important; font-size: 13px; }
        .ql-snow .ql-size .ql-picker-item[data-value="14px"]::before { content: '14px' !important; font-size: 14px; }
        .ql-snow .ql-size .ql-picker-item[data-value="15px"]::before { content: '15px' !important; font-size: 15px; }
        .ql-snow .ql-size .ql-picker-item[data-value="16px"]::before { content: '16px' !important; font-size: 16px; }
        .ql-snow .ql-size .ql-picker-item[data-value="18px"]::before { content: '18px' !important; font-size: 18px; }
        .ql-snow .ql-size .ql-picker-item[data-value="20px"]::before { content: '20px' !important; font-size: 20px; }
        .ql-snow .ql-size .ql-picker-item[data-value="25px"]::before { content: '25px' !important; font-size: 25px; }
        .ql-snow .ql-size .ql-picker-item[data-value="30px"]::before { content: '30px' !important; font-size: 30px; }
        .ql-snow .ql-size .ql-picker-label[data-value="13px"]::before { content: '13px' !important; }
        .ql-snow .ql-size .ql-picker-label[data-value="14px"]::before { content: '14px' !important; }
        .ql-snow .ql-size .ql-picker-label[data-value="15px"]::before { content: '15px' !important; }
        .ql-snow .ql-size .ql-picker-label[data-value="16px"]::before { content: '16px' !important; }
        .ql-snow .ql-size .ql-picker-label[data-value="18px"]::before { content: '18px' !important; }
        .ql-snow .ql-size .ql-picker-label[data-value="20px"]::before { content: '20px' !important; }
        .ql-snow .ql-size .ql-picker-label[data-value="25px"]::before { content: '25px' !important; }
        .ql-snow .ql-size .ql-picker-label[data-value="30px"]::before { content: '30px' !important; }
        .ql-snow .ql-size .ql-picker-label:not([data-value])::before { content: 'Tamaño' !important; }

        /* Fuentes Quill (especificidad alta para superar quill.snow.css) */
        .ql-snow .ql-picker.ql-font .ql-picker-label::before,
        .ql-snow .ql-picker.ql-font .ql-picker-item::before { content: 'Predeterminada' !important; }
        .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="Roboto"]::before,
        .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="Roboto"]::before { content: 'Roboto' !important; font-family: 'Roboto', sans-serif !important; }
        .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="Roboto Slab"]::before,
        .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="Roboto Slab"]::before { content: 'Roboto Slab' !important; font-family: 'Roboto Slab', serif !important; }
        .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="Great Vibes"]::before,
        .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="Great Vibes"]::before { content: 'Great Vibes' !important; font-family: 'Great Vibes', cursive !important; }
        .ql-font-Roboto { font-family: 'Roboto', sans-serif; }
        .ql-font-Roboto-Slab { font-family: 'Roboto Slab', serif; }
        .ql-font-Great-Vibes { font-family: 'Great Vibes', cursive; }

        /* Preview */
        .preview-label {
            font-size: 13px;
            font-weight: 600;
            color: #555;
            margin-bottom: 10px;
        }

        .memo-preview {
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
        }
        .memo-preview img {
            width: 100%;
            display: block;
        }

        .preview-header {
            text-align: center;
            color: #1a237e;
            font-size: 9px;
            font-weight: bold;
            border-bottom: 1px solid #1a237e;
            padding-bottom: 6px;
            margin-bottom: 6px;
        }

        .preview-title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            margin: 6px 0 2px;
        }

        .preview-correlativo {
            text-align: center;
            font-size: 8px;
            margin-bottom: 8px;
            color: #555;
        }

        .preview-two-col {
            display: flex;
            gap: 10px;
            margin-bottom: 8px;
            font-size: 8px;
        }

        .preview-two-col .left { flex: 0 0 45%; border-right: 1px solid #ccc; padding-right: 8px; }
        .preview-two-col .right { flex: 1; }

        .preview-body {
            font-size: 8px;
            text-align: justify;
            line-height: 1.4;
            border-top: 1px solid #1a237e;
            padding-top: 6px;
        }

        /* Botones */
        .btn-save {
            background: #28a745;
            color: white;
            border: none;
            padding: 7px 18px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-save:hover { background: #218838; }

        .btn-back {
            background: #6c757d;
            color: white;
            border: none;
            padding: 7px 14px;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-back:hover { background: #5a6268; }

        .btn-row {
            display: flex;
            gap: 8px;
            align-items: center;
            margin-top: 10px;
        }

        .action-btn {
            min-width: 170px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .45rem;
            border-radius: 8px;
            border: none;
            padding: .55rem 1.1rem;
            font-size: .85rem;
            font-weight: 600;
            line-height: 1.25;
            text-decoration: none;
            cursor: pointer;
            transition: filter .15s, transform .15s;
            white-space: nowrap;
            color: #fff;
        }

        .action-btn:hover {
            transform: translateY(-1px);
            filter: brightness(1.1);
            color: #fff;
        }

        .action-preview { background: #0891b2; }
        .action-save { background: #16a34a; }
        .action-cancel {
            min-width: 108px;
            background: #6b7280;
        }

        #modalPrevisualizacionMemo .modal-dialog {
            margin-top: 92px;
            margin-bottom: 16px;
            max-width: min(1240px, calc(100vw - 28px));
        }

        #modalPrevisualizacionMemo .modal-content {
            max-height: calc(100vh - 92px);
        }

        #modalPrevisualizacionMemo .modal-body {
            overflow: hidden;
        }

        #modalPrevisualizacionMemo #iframePrevisualizacionMemo {
            height: calc(100vh - 170px) !important;
        }

        @media (max-width: 900px) {
            .main-grid { grid-template-columns: 1fr; }
            .preview-panel { position: static; max-height: none; }
        }
    </style>
</head>
<body>
    <nav class="top-bar">
        <div class="brand">
            <img src="/img/104/logo_medicina_sf.png" alt="Logo">
            <div>
                <div class="brand-text">Certificaciones Digitales</div>
                <div class="brand-sub">Diseñador de Memorándum</div>
            </div>
        </div>
        <a href="{{ route('memorandums.show', $memorandum->id) }}" class="back-btn">
            ← Volver al Memorándum
        </a>
    </nav>
<div class="container">

    <div class="header">
        <div>
            <h1><i style="font-style:normal;">✏️</i> Diseñar Memorándum</h1>
            <div class="sub">{{ $memorandum->evento }}</div>
        </div>
    </div>

    <form id="formDiseno" method="POST" action="{{ route('memorandums.guardarDiseno', $memorandum->id) }}">
        @csrf
        <input type="hidden" name="encabezado_html" id="inputEncabezado">
        <input type="hidden" name="cab_html" id="inputCab">
        <input type="hidden" name="destinatario_html" id="inputDestinatario">
        <input type="hidden" name="tenor_html" id="inputTenor">

        <div class="main-grid">

            <!-- EDITORES -->
            <div class="editor-panel">
                <div class="section-title">Bloques del Memorandum</div>

                <!-- BLOQUE 1: ENCABEZADO -->
                <div class="editor-wrapper">
                    <label class="editor-label">
                        1. Encabezado <span>(texto central entre los logos)</span>
                    </label>
                    <div id="editorEncabezado"></div>
                </div>

                <!-- BLOQUE 2: CAB (izquierda) -->
                <div class="editor-wrapper">
                    <label class="editor-label">
                        2. Bloque izquierdo <span>(debajo del correlativo, a la izquierda)</span>
                    </label>
                    <div id="editorCab"></div>
                </div>

                <!-- BLOQUE 3: DESTINATARIO (derecha) -->
                <div class="editor-wrapper">
                    <label class="editor-label">
                        3. Destinatario <span>(bloque derecho — Señor(a):...)</span>
                    </label>
                    <div class="variables-bar">
                        <span class="var-label">Insertar:</span>
                        <button type="button" class="var-btn" onclick="insertVar('quillDestinatario', '@{{grado}}')">grado</button>
                        <button type="button" class="var-btn" onclick="insertVar('quillDestinatario', '@{{nombre}}')">nombre</button>
                        <button type="button" class="var-btn" onclick="insertVar('quillDestinatario', '@{{paterno}}')">paterno</button>
                        <button type="button" class="var-btn" onclick="insertVar('quillDestinatario', '@{{materno}}')">materno</button>
                        <button type="button" class="var-btn" onclick="insertVar('quillDestinatario', '@{{tipo}}')">tipo</button>
                        <button type="button" class="var-btn" onclick="insertVar('quillDestinatario', '@{{fecha_actual}}')">fecha actual</button>
                    </div>
                    <div class="ql-container tall" id="editorDestinatario" style="min-height:0"></div>
                </div>

                <!-- BLOQUE 4: TENOR -->
                <div class="editor-wrapper">
                    <label class="editor-label">
                        4. Tenor <span>(cuerpo del memorandum)</span>
                    </label>
                    <div class="variables-bar">
                        <span class="var-label">Insertar:</span>
                        <button type="button" class="var-btn" onclick="insertVar('quillTenor', '@{{grado}}')">grado</button>
                        <button type="button" class="var-btn" onclick="insertVar('quillTenor', '@{{nombre}}')">nombre</button>
                        <button type="button" class="var-btn" onclick="insertVar('quillTenor', '@{{paterno}}')">paterno</button>
                        <button type="button" class="var-btn" onclick="insertVar('quillTenor', '@{{materno}}')">materno</button>
                        <button type="button" class="var-btn" onclick="insertVar('quillTenor', '@{{tipo}}')">tipo</button>
                        <button type="button" class="var-btn" onclick="insertVar('quillTenor', '@{{resolucion}}')">resolución</button>
                        <button type="button" class="var-btn" onclick="insertVar('quillTenor', '@{{fecha_resolucion}}')">fecha resolución</button>
                        <button type="button" class="var-btn" onclick="insertVar('quillTenor', '@{{detalle}}')">detalle</button>
                        <button type="button" class="var-btn" onclick="insertVar('quillTenor', '@{{evento}}')">evento</button>
                    </div>
                    <div id="editorTenor"></div>
                </div>

                <div class="btn-row">
                    <a href="{{ route('memorandums.show', $memorandum->id) }}" class="action-btn action-cancel">
                        <i class="bi bi-arrow-left"></i> Cancelar
                    </a>
                    <button type="button" id="btnPrevisualizarMemo" class="action-btn action-preview">
                        <i class="bi bi-eye"></i> Previsualización
                    </button>
                    <button type="submit" class="action-btn action-save">
                        <i class="bi bi-save"></i> Guardar diseño
                    </button>

                </div>
            </div>

            <!-- PREVIEW -->
            <div class="preview-panel">
                <div class="preview-label" style="font-size:11px; font-weight:600; color:#555; margin-bottom:6px;">Vista previa (esquema)</div>
                <div class="memo-preview">
                    <img src="{{ asset('fondo-memo/fondomemo.jpg') }}" alt="Fondo memorandum" style="opacity:0.85;">
                </div>
                <div style="margin-top:8px; font-size:10px; color:#888; text-align:center;">Los bloques se sobrepondrán al fondo en el PDF generado.</div>
            </div>

        </div>
    </form>

    <form id="formPrevisualizacionMemo"
          method="POST"
          action="{{ route('memorandums.previsualizarDiseno', $memorandum->id) }}"
          target="iframePrevisualizacionMemo"
          class="d-none">
        @csrf
        <input type="hidden" name="encabezado_html" id="previewEncabezadoHtml">
        <input type="hidden" name="cab_html" id="previewCabHtml">
        <input type="hidden" name="destinatario_html" id="previewDestinatarioHtml">
        <input type="hidden" name="tenor_html" id="previewTenorHtml">
    </form>

    <div class="modal fade" id="modalPrevisualizacionMemo" tabindex="-1" aria-labelledby="modalPrevisualizacionMemoLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content" style="border-radius:14px; overflow:hidden;">
                <div class="modal-header py-2 px-3">
                    <h5 class="modal-title mb-0" id="modalPrevisualizacionMemoLabel">Previsualización del memorándum</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-2" style="background:#f4f6fb;">
                    <iframe
                        id="iframePrevisualizacionMemo"
                        name="iframePrevisualizacionMemo"
                        title="Previsualización del diseño de memorándum"
                        style="width:100%; height:78vh; border:0; border-radius:10px; background:#fff;"
                        loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
    // Registrar fuentes y tamaños con StyleAttributor
    var FontStyle = Quill.import('attributors/style/font');
    FontStyle.whitelist = ['Roboto', 'Roboto Slab', 'Great Vibes'];
    Quill.register(FontStyle, true);

    var SizeStyle = Quill.import('attributors/style/size');
    SizeStyle.whitelist = ['13px', '14px', '15px', '16px', '18px', '20px', '25px', '30px'];
    Quill.register(SizeStyle, true);

    var toolbarComun = [
        [{ 'font': ['Roboto', 'Roboto Slab', 'Great Vibes'] }],
        [{ 'size': ['13px', '14px', '15px', '16px', '18px', '20px', '25px', '30px'] }],
        ['bold', 'italic', 'underline'],
        [{ 'align': [] }],
        [{ 'color': [] }],
        ['clean']
    ];

    var toolbarConLista = [
        [{ 'font': ['Roboto', 'Roboto Slab', 'Great Vibes'] }],
        [{ 'size': ['13px', '14px', '15px', '16px', '18px', '20px', '25px', '30px'] }],
        ['bold', 'italic', 'underline'],
        [{ 'align': [] }],
        [{ 'color': [] }],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        ['clean']
    ];

    var quillEncabezado = new Quill('#editorEncabezado', {
        theme: 'snow',
        modules: { toolbar: toolbarComun }
    });

    var quillCab = new Quill('#editorCab', {
        theme: 'snow',
        modules: { toolbar: toolbarComun }
    });

    var quillDestinatario = new Quill('#editorDestinatario', {
        theme: 'snow',
        modules: { toolbar: toolbarComun }
    });

    var quillTenor = new Quill('#editorTenor', {
        theme: 'snow',
        modules: { toolbar: toolbarConLista }
    });

    // Mapa de instancias para insertVar
    var quillMap = {
        'quillDestinatario': quillDestinatario,
        'quillTenor': quillTenor
    };

    // Insertar variable en el editor activo
    function insertVar(editorName, text) {
        var q = quillMap[editorName];
        if (!q) return;
        var range = q.getSelection(true);
        q.insertText(range.index, text, 'user');
        q.setSelection(range.index + text.length, 0);
    }

    // Contenido por defecto si no hay datos guardados
    var defaultEncabezado = '<p class="ql-align-center"><strong style="font-size: 20px; font-family: &quot;Roboto Slab&quot;;">UNIVERSIDAD MAYOR DE SAN ANDRÉS</strong></p><p class="ql-align-center"><span style="font-size: 14px; font-family: &quot;Roboto Slab&quot;;">FACULTAD DE MEDICINA, ENFERMERÍA, NUTRICIÓN Y TECNOLOGÍA MÉDICA</span></p><p class="ql-align-center"><span style="font-size: 16px; font-family: &quot;Roboto Slab&quot;;">VICEDECANATO</span></p>';
    var defaultCab = '<p class="ql-align-center"><strong style="font-size: 13px; font-family: &quot;Roboto Slab&quot;;">UNIVERSIDAD MAYOR DE SAN ANDRÉS</strong></p><p class="ql-align-center"><span style="font-size: 13px; font-family: &quot;Roboto Slab&quot;;">FACULTAD DE MEDICINA, ENFERMERÍA,</span></p><p class="ql-align-center"><span style="font-size: 13px; font-family: &quot;Roboto Slab&quot;;">NUTRICIÓN Y TECNOLOGÍA MÉDICA</span></p><p class="ql-align-center"><strong style="font-size: 13px; font-family: &quot;Roboto Slab&quot;;">VICEDECANATO</strong></p>';
    var defaultDestinatario = '<p><span style="font-family: &quot;Roboto Slab&quot;;">Señor(a):</span></p><p><span style="font-family: &quot;Roboto Slab&quot;;">@{{grado}} @{{nombre}} @{{paterno}} @{{materno}}</span></p><p><strong style="font-family: &quot;Roboto Slab&quot;;">@{{tipo}}</strong><span style="font-family: &quot;Roboto Slab&quot;;"> - Facultad de Medicina, Enfermería, Nutrición y Tecnología Médica</span></p><p><span style="font-family: &quot;Roboto Slab&quot;;">Presente.-</span></p><p><br></p><p><span style="font-family: &quot;Roboto Slab&quot;;">La Paz, @{{fecha_actual}}</span></p>';
    var defaultTenor = '<p><strong style="font-family: &quot;Roboto Slab&quot;; font-size: 16px;">De mi consideración:</strong></p><p><span style="font-family: &quot;Roboto Slab&quot;; font-size: 16px;">En cumplimiento a la </span><strong style="font-family: &quot;Roboto Slab&quot;; font-size: 16px;">@{{resolucion}}</strong><span style="font-family: &quot;Roboto Slab&quot;; font-size: 16px;"> de fecha @{{fecha_resolucion}}, de la Facultad de Medicina, Enfermería, Nutrición y Tecnología Médica, comunico a usted que ha sido designado para conformar el </span><strong style="font-family: &quot;Roboto Slab&quot;; font-size: 16px;">@{{detalle}}</strong><span style="font-family: &quot;Roboto Slab&quot;; font-size: 16px;"> para la </span><strong style="font-family: &quot;Roboto Slab&quot;; font-size: 16px;">@{{evento}}</strong><span style="font-family: &quot;Roboto Slab&quot;; font-size: 16px;">.</span></p><p><span style="font-family: &quot;Roboto Slab&quot;; font-size: 16px;">Deseandole éxitos en las funciones asignadas, saludo a usted muy cordialmente.</span></p><p><strong style="font-family: &quot;Roboto Slab&quot;; font-size: 16px;">Atentamente</strong></p>';

    var savedEncabezado  = @json($memorandum->encabezado_html);
    var savedCab         = @json($memorandum->cab_html);
    var savedDestinatario= @json($memorandum->destinatario_html);
    var savedTenor       = @json($memorandum->tenor_html);

    setTimeout(function() {
        var rootEnc = quillEncabezado.root;
        rootEnc.innerHTML = savedEncabezado || defaultEncabezado;
        quillEncabezado.history.clear();

        var rootCab = quillCab.root;
        rootCab.innerHTML = savedCab || defaultCab;
        quillCab.history.clear();

        var rootDest = quillDestinatario.root;
        rootDest.innerHTML = savedDestinatario || defaultDestinatario;
        quillDestinatario.history.clear();

        var rootTenor = quillTenor.root;
        rootTenor.innerHTML = savedTenor || defaultTenor;
        quillTenor.history.clear();
    }, 50);

    // Al enviar, copiar HTML a los inputs ocultos
    document.getElementById('formDiseno').addEventListener('submit', function() {
        document.getElementById('inputEncabezado').value   = quillEncabezado.root.innerHTML;
        document.getElementById('inputCab').value          = quillCab.root.innerHTML;
        document.getElementById('inputDestinatario').value = quillDestinatario.root.innerHTML;
        document.getElementById('inputTenor').value        = quillTenor.root.innerHTML;
    });

    var btnPrevisualizarMemo = document.getElementById('btnPrevisualizarMemo');
    var formPrevisualizacionMemo = document.getElementById('formPrevisualizacionMemo');
    var modalPrevisualizacionMemo = document.getElementById('modalPrevisualizacionMemo');

    if (btnPrevisualizarMemo && formPrevisualizacionMemo) {
        btnPrevisualizarMemo.addEventListener('click', function() {
            document.getElementById('previewEncabezadoHtml').value = quillEncabezado.root.innerHTML;
            document.getElementById('previewCabHtml').value = quillCab.root.innerHTML;
            document.getElementById('previewDestinatarioHtml').value = quillDestinatario.root.innerHTML;
            document.getElementById('previewTenorHtml').value = quillTenor.root.innerHTML;

            if (window.bootstrap && modalPrevisualizacionMemo) {
                bootstrap.Modal.getOrCreateInstance(modalPrevisualizacionMemo).show();
            }

            formPrevisualizacionMemo.submit();
        });
    }
</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

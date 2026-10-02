<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diseñar Certificado - {{ $memorandum->evento }}</title>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Roboto+Slab&family=Great+Vibes&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f1f5f9; padding: 12px; padding-top: 68px; }
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
        .header { background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 100%); padding: 10px 14px; border-radius: 8px; margin-bottom: 12px; box-shadow: 0 2px 8px rgba(30,58,138,.2); color: #fff; }
        .header h1 { color: #fff; margin-bottom: 4px; font-size: 1.1rem; }
        .memo-info { display: flex; gap: 14px; color: rgba(255,255,255,.8); font-size: 12px; margin-top: 5px; flex-wrap: wrap; }
        .main-grid { display: grid; grid-template-columns: 1fr 320px; gap: 12px; margin-bottom: 12px; }
        .editor-panel { background: white; border-radius: 8px; padding: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .preview-panel { background: white; border-radius: 8px; padding: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); position: sticky; top: 8px; max-height: calc(100vh - 60px); overflow-y: auto; }
        .section-title { font-size: 0.95rem; font-weight: bold; color: #333; margin-bottom: 8px; padding-bottom: 5px; border-bottom: 2px solid #667eea; }
        .editor-wrapper { margin-bottom: 10px; }
        .editor-label { font-weight: 600; color: #555; margin-bottom: 4px; display: block; font-size: 11px; text-transform: uppercase; letter-spacing: 0.04em; }
        .toolbar-variables { background: #f0f2ff; padding: 4px 8px; border-radius: 5px; margin-bottom: 5px; border: 1px solid #d0d5f5; display: flex; flex-wrap: wrap; align-items: center; gap: 3px; }
        .toolbar-variables p { font-size: 10px; color: #666; margin-bottom: 0; margin-right: 4px; font-weight: 600; white-space: nowrap; }
        .variable-btn {
            display: inline-block; padding: 2px 7px; margin: 0;
            background: #667eea;
            color: white; border: none; border-radius: 3px; cursor: pointer;
            font-size: 10px; font-weight: 600; transition: transform 0.2s;
        }
        .variable-btn:hover { transform: scale(1.05); }
        #editorEncabezado { height: 150px; background: white; border: 1px solid #ccc; }
        #editorTenor { height: 280px; background: white; border: 1px solid #ccc; }
        .preview-fondo { width: 100%; border-radius: 8px; margin-bottom: 15px; border: 2px solid #e0e0e0; }
        .preview-info { background: #f8f9fa; padding: 12px; border-radius: 8px; margin-bottom: 15px; font-size: 13px; color: #666; }
        .preview-info strong { color: #333; }
        .fondo-actual { text-align: center; padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 15px; }
        .fondo-actual h4 { color: #333; margin-bottom: 10px; }
        .botones-accion { background: white; padding: 10px 14px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; gap: 10px; justify-content: space-between; }
        .btn { padding: 7px 20px; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: transform 0.2s; text-decoration: none; display: inline-block; }
        .btn:hover { transform: scale(1.05); }
        .btn-primary  { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .btn-secondary { background: #e0e0e0; color: #333; }
        .btn-success { background: #4CAF50; color: white; }
        .botones-accion-right { display: flex; gap: 10px; align-items: center; justify-content: flex-end; flex-wrap: wrap; }
        .action-btn {
            min-width: 176px;
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
        }
        .action-btn:hover { transform: translateY(-1px); filter: brightness(1.1); color: #fff; }
        .action-cancel { min-width: 108px; background: #6b7280; color: #fff; }
        .action-preview { background: #0891b2; color: #fff; }
        .action-save { background: #16a34a; color: #fff; }

        #modalPrevisualizacionPdfMemo .modal-dialog {
            margin-top: 92px;
            margin-bottom: 16px;
            max-width: min(1240px, calc(100vw - 28px));
        }

        #modalPrevisualizacionPdfMemo .modal-content {
            max-height: calc(100vh - 92px);
        }

        #modalPrevisualizacionPdfMemo .modal-body {
            overflow: hidden;
        }

        #modalPrevisualizacionPdfMemo #iframePrevisualizacionPdfMemo {
            height: calc(100vh - 170px) !important;
        }
        @media (max-width: 768px) {
            .action-btn { min-width: 148px; font-size: 12px; padding: 7px 14px; }
            .action-cancel { min-width: 112px; }
        }
        .alert { padding: 7px 14px; border-radius: 6px; margin-bottom: 10px; font-size: 12px; }
        .alert-info { background: #e3f2fd; color: #1976d2; border-left: 4px solid #1976d2; }
        .ql-toolbar { background: white; border: 1px solid #ccc; border-bottom: none; border-radius: 8px 8px 0 0; }
        .ql-container { border-radius: 0 0 8px 8px; font-family: 'Roboto', sans-serif; }
        /* Fuentes en el editor */
        .ql-editor [style*="font-family: Roboto Slab"],
        .ql-editor [style*='font-family: "Roboto Slab"'],
        .ql-editor [style*="font-family: &quot;Roboto Slab&quot;"] { font-family: 'Roboto Slab', serif !important; }
        .ql-editor [style*="font-family: Great Vibes"],
        .ql-editor [style*='font-family: "Great Vibes"'],
        .ql-editor [style*="font-family: &quot;Great Vibes&quot;"] { font-family: 'Great Vibes', cursive !important; }
        .ql-editor [style*="font-family: Roboto;"],
        .ql-editor [style*='font-family: "Roboto"'] { font-family: 'Roboto', sans-serif !important; }
        /* Tamaños */
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
        /* Fuentes picker */
        .ql-snow .ql-picker.ql-font .ql-picker-label::before,
        .ql-snow .ql-picker.ql-font .ql-picker-item::before { content: 'Predeterminada' !important; }
        .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="Roboto"]::before,
        .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="Roboto"]::before { content: 'Roboto' !important; font-family: 'Roboto', sans-serif !important; }
        .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="Roboto Slab"]::before,
        .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="Roboto Slab"]::before { content: 'Roboto Slab' !important; font-family: 'Roboto Slab', serif !important; }
        .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="Great Vibes"]::before,
        .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="Great Vibes"]::before { content: 'Great Vibes' !important; font-family: 'Great Vibes', cursive !important; }
    </style>
</head>
<body>
    <nav class="top-bar">
        <div class="brand">
            <img src="/img/104/logo_medicina_sf.png" alt="Logo">
            <div>
                <div class="brand-text">Certificaciones Digitales</div>
                <div class="brand-sub">Diseñador de Certificado</div>
            </div>
        </div>
        <a href="{{ route('memorandums.show', $memorandum->id) }}" class="back-btn">
            ← Volver al Memorándum
        </a>
    </nav>
    <div class="container">
        <div class="header">
            <h1><i style="font-style:normal;">✏️</i> Diseñar Certificado desde Memorándum</h1>
            <div class="memo-info">
                <span><strong>Memorándum:</strong> {{ $memorandum->evento }}</span>
                <span><strong>Detalle:</strong> {{ $memorandum->detalle }}</span>
                <span><strong>Fondo:</strong> Fondo Memorándum (fijo)</span>
            </div>
        </div>

        <div class="alert alert-info">
            <strong>💡 Instrucciones:</strong> Usa los botones de variables para insertar datos dinámicos de cada persona. El texto fijo aparecerá igual en todos los certificados.
        </div>

        <form action="{{ route('memorandums.guardarDisenoCert', $memorandum->id) }}" method="POST" id="formDiseno">
            @csrf

            <div class="main-grid">
                <!-- Panel Editor -->
                <div class="editor-panel">

                    <!-- ENCABEZADO -->
                    <div class="editor-wrapper">
                        <label class="editor-label">📝 ENCABEZADO (Entre los logos)</label>
                        <div class="toolbar-variables">
                            <p>📌 Variables disponibles para encabezado:</p>
                            <button type="button" class="variable-btn" onclick="insertarVariable('enc','@{{evento}}')">📅 Evento</button>
                            <button type="button" class="variable-btn" onclick="insertarVariable('enc','@{{detalle}}')">📄 Detalle</button>
                            <button type="button" class="variable-btn" onclick="insertarVariable('enc','@{{resolucion}}')">📋 Resolución</button>
                        </div>
                        <div id="editorEncabezado"></div>
                    </div>

                    <!-- TENOR -->
                    <div class="editor-wrapper">
                        <label class="editor-label">📜 TENOR (Cuerpo del certificado)</label>
                        <div class="toolbar-variables">
                            <p>📌 Variables disponibles para tenor:</p>
                            <button type="button" class="variable-btn" onclick="insertarVariable('ten','@{{grado}}')">🎓 Grado</button>
                            <button type="button" class="variable-btn" onclick="insertarVariable('ten','@{{nombre}}')">👤 Nombre</button>
                            <button type="button" class="variable-btn" onclick="insertarVariable('ten','@{{paterno}}')">👤 A. Paterno</button>
                            <button type="button" class="variable-btn" onclick="insertarVariable('ten','@{{materno}}')">👤 A. Materno</button>
                            <button type="button" class="variable-btn" onclick="insertarVariable('ten','@{{tipo}}')">🏷️ Tipo</button>
                            <button type="button" class="variable-btn" onclick="insertarVariable('ten','@{{evento}}')">📅 Evento</button>
                            <button type="button" class="variable-btn" onclick="insertarVariable('ten','@{{detalle}}')">📄 Detalle</button>
                            <button type="button" class="variable-btn" onclick="insertarVariable('ten','@{{resolucion}}')">📋 Resolución</button>
                            <button type="button" class="variable-btn" onclick="insertarVariable('ten','@{{fecha_resolucion}}')">📆 Fecha Resolución</button>
                            <button type="button" class="variable-btn" onclick="insertarVariable('ten','@{{carrera}}')">🏫 Carrera</button>
                            <button type="button" class="variable-btn" onclick="insertarVariable('ten','@{{departamento}}')">🏛️ Departamento</button>
                            <button type="button" class="variable-btn" onclick="insertarVariable('ten','@{{catedra}}')">📚 Cátedra</button>
                        </div>
                        <div id="editorTenor"></div>
                    </div>

                    <input type="hidden" name="cert_encabezado_html" id="cert_encabezado_html">
                    <input type="hidden" name="cert_tenor_html" id="cert_tenor_html">
                </div>

                <!-- Panel Preview -->
                <div class="preview-panel">
                    <div class="section-title">👁️ Vista Previa</div>
                    <div class="fondo-actual">
                        <h4>Fondo Memorándum</h4>
                        <span style="font-size:12px; color:#666;">📄 Vertical (Portrait)</span>
                    </div>
                    <img src="{{ asset('fondo-memo/fondomemocer.jpg') }}" alt="Fondo del certificado" class="preview-fondo">
                    <div class="preview-info">
                        <strong>📍 Fondo fixo para certificados de memorándum.</strong>
                    </div>
                </div>
            </div>

            <div class="botones-accion">
                <a href="{{ route('memorandums.show', $memorandum->id) }}" class="action-btn action-cancel">
                    <i class="bi bi-arrow-left"></i> Cancelar
                </a>
                <div class="botones-accion-right">
                    <button type="button" id="btnPrevisualizarDisenoMemo" class="action-btn action-preview">
                        <i class="bi bi-eye"></i> Previsualización
                    </button>
                    <button type="submit" class="action-btn action-save">
                        <i class="bi bi-save"></i> Guardar Diseño
                    </button>
                </div>
            </div>
        </form>

        <form id="formPrevisualizacionPdfMemo"
              action="{{ route('memorandums.previsualizarDisenoCert', $memorandum->id) }}"
              method="POST"
              target="iframePrevisualizacionPdfMemo"
              class="d-none">
            @csrf
            <input type="hidden" name="cert_encabezado_html" id="preview_cert_encabezado_html">
            <input type="hidden" name="cert_tenor_html" id="preview_cert_tenor_html">
        </form>

        <div class="modal fade" id="modalPrevisualizacionPdfMemo" tabindex="-1" aria-labelledby="modalPrevisualizacionPdfMemoLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content" style="border-radius:14px; overflow:hidden;">
                    <div class="modal-header py-2 px-3">
                        <h5 class="modal-title mb-0" id="modalPrevisualizacionPdfMemoLabel">Previsualización del diseño</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body p-2" style="background:#f4f6fb;">
                        <iframe
                            id="iframePrevisualizacionPdfMemo"
                            name="iframePrevisualizacionPdfMemo"
                            title="Previsualización del certificado desde memorándum"
                            style="width:100%; height:78vh; border:0; border-radius:10px; background:#fff;"
                            loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        var FontStyle = Quill.import('attributors/style/font');
        FontStyle.whitelist = ['Roboto', 'Roboto Slab', 'Great Vibes'];
        Quill.register(FontStyle, true);

        var SizeStyle = Quill.import('attributors/style/size');
        SizeStyle.whitelist = ['13px', '14px', '15px', '16px', '18px', '20px', '25px', '30px'];
        Quill.register(SizeStyle, true);

        var toolbarOpciones = [
            [{ 'font': ['Roboto', 'Roboto Slab', 'Great Vibes'] }],
            [{ 'size': ['13px', '14px', '15px', '16px', '18px', '20px', '25px', '30px'] }],
            ['bold', 'italic', 'underline'],
            [{ 'align': [] }],
            [{ 'color': [] }],
            ['clean']
        ];

        var quillEncabezado = new Quill('#editorEncabezado', {
            theme: 'snow',
            modules: { toolbar: toolbarOpciones }
        });

        var quillTenor = new Quill('#editorTenor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'font': ['Roboto', 'Roboto Slab', 'Great Vibes'] }],
                    [{ 'size': ['13px', '14px', '15px', '16px', '18px', '20px', '25px', '30px'] }],
                    ['bold', 'italic', 'underline'],
                    [{ 'align': [] }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'color': [] }],
                    ['clean']
                ]
            }
        });

        // Plantilla encabezado genérica
        var plantillaEncabezado = '<p class="ql-align-center"><span style="color: rgb(0, 41, 102); font-family: &quot;Roboto Slab&quot;; font-size: 20px;">UNIVERSIDAD MAYOR DE SAN ANDRÉS</span></p><p class="ql-align-center"><span style="color: rgb(0, 41, 102); font-family: &quot;Roboto Slab&quot;; font-size: 14px;">FACULTAD DE MEDICINA, ENFERMERÍA, NUTRICIÓN Y TECNOLOGÍA MÉDICA</span></p><p class="ql-align-center"><span style="font-size: 14px; font-family: &quot;Roboto Slab&quot;;">VICEDECANATO</span></p><p><br></p>';

        var plantillaTenor = '<p class="ql-align-justify"><span style="font-size: 18px; font-family: &quot;Roboto Slab&quot;;">Se confiere el presente:</span></p><p class="ql-align-justify"><br></p><p class="ql-align-center"><strong style="font-size: 20px; font-family: &quot;Roboto Slab&quot;;">CERTIFICADO</strong></p><p class="ql-align-center"><br></p><p class="ql-align-center"><span style="font-family: &quot;Great Vibes&quot;; font-size: 30px;">A: @{{nombre}} @{{paterno}} @{{materno}}</span></p><p class="ql-align-center"><br></p><p class="ql-align-justify"><span style="font-size: 18px; font-family: &quot;Roboto Slab&quot;;">Por su participación en calidad de:</span></p><p class="ql-align-center"><br></p><p class="ql-align-center"><strong style="font-family: &quot;Roboto Slab&quot;; font-size: 20px;">@{{tipo}}</strong></p><p class="ql-align-center"><br></p><p class="ql-align-justify"><span style="font-size: 18px; font-family: &quot;Roboto Slab&quot;;">En la "@{{evento}}", en cumplimiento a la @{{resolucion}} de fecha @{{fecha_resolucion}}.</span></p>';

        @php
            $encVacio = empty(trim(strip_tags($memorandum->cert_encabezado_html ?? '')));
            $tenVacio = empty(trim(strip_tags($memorandum->cert_tenor_html ?? '')));
        @endphp

        setTimeout(function() {
            @if(!$encVacio)
                quillEncabezado.root.innerHTML = {!! json_encode($memorandum->cert_encabezado_html) !!};
            @else
                quillEncabezado.root.innerHTML = plantillaEncabezado;
            @endif
            quillEncabezado.history.clear();

            @if(!$tenVacio)
                quillTenor.root.innerHTML = {!! json_encode($memorandum->cert_tenor_html) !!};
            @else
                quillTenor.root.innerHTML = plantillaTenor;
            @endif
            quillTenor.history.clear();
        }, 50);

        function insertarVariable(editor, variable) {
            if (editor === 'enc') {
                var range = quillEncabezado.getSelection(true);
                quillEncabezado.insertText(range.index, variable);
                quillEncabezado.setSelection(range.index + variable.length);
            } else {
                var range = quillTenor.getSelection(true);
                quillTenor.insertText(range.index, variable);
                quillTenor.setSelection(range.index + variable.length);
            }
        }

        document.getElementById('formDiseno').addEventListener('submit', function() {
            document.getElementById('cert_encabezado_html').value = quillEncabezado.root.innerHTML;
            document.getElementById('cert_tenor_html').value = quillTenor.root.innerHTML;
        });

        var btnPrevisualizarDisenoMemo = document.getElementById('btnPrevisualizarDisenoMemo');
        var modalPrevisualizacionPdfMemo = document.getElementById('modalPrevisualizacionPdfMemo');
        var formPrevisualizacionPdfMemo = document.getElementById('formPrevisualizacionPdfMemo');
        var previewCertEncabezadoHtml = document.getElementById('preview_cert_encabezado_html');
        var previewCertTenorHtml = document.getElementById('preview_cert_tenor_html');

        if (btnPrevisualizarDisenoMemo && formPrevisualizacionPdfMemo) {
            btnPrevisualizarDisenoMemo.addEventListener('click', function() {
                if (previewCertEncabezadoHtml) {
                    previewCertEncabezadoHtml.value = quillEncabezado.root.innerHTML;
                }
                if (previewCertTenorHtml) {
                    previewCertTenorHtml.value = quillTenor.root.innerHTML;
                }

                if (window.bootstrap && modalPrevisualizacionPdfMemo) {
                    bootstrap.Modal.getOrCreateInstance(modalPrevisualizacionPdfMemo).show();
                }

                formPrevisualizacionPdfMemo.submit();
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

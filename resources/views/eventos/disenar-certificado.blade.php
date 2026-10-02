<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diseñar Certificado - {{ $evento->evento }}</title>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Roboto+Slab&family=Great+Vibes&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            padding: 12px;
            padding-top: 64px;
        }

        /* Top Bar Fixed */
        .top-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 52px;
            background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 100%);
            color: #fff;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,.15);
            z-index: 1000;
            gap: 1rem;
        }
        .top-bar .logo-section {
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .top-bar .logo-icon {
            width: 32px;
            height: 32px;
            background: rgba(255,255,255,.15);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }
        .top-bar .brand-text {
            font-size: .9rem;
            font-weight: 600;
        }
        .top-bar .brand-sub {
            font-size: .7rem;
            opacity: .8;
        }

        .container {
            max-width: 1600px;
            margin: 0 auto;
        }

        .header {
            background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 100%);
            color: #fff;
            padding: 1rem 1.25rem;
            border-radius: 10px;
            margin-bottom: 12px;
            box-shadow: 0 4px 12px rgba(30,58,138,.2);
        }

        .header h1 {
            color: #fff;
            margin-bottom: 6px;
            font-size: 1.15rem;
            font-weight: 700;
        }

        .evento-info {
            display: flex;
            gap: 14px;
            color: rgba(255,255,255,.85);
            font-size: 12px;
            margin-top: 5px;
        }

        .main-grid {
            display: grid;
            grid-template-columns: 1fr 320px;
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
            font-size: 0.95rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
            padding-bottom: 6px;
            border-bottom: 2px solid #667eea;
        }

        .editor-wrapper {
            margin-bottom: 10px;
        }

        .editor-label {
            font-weight: 600;
            color: #555;
            margin-bottom: 4px;
            display: block;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .toolbar-variables {
            background: #f0f2ff;
            padding: 5px 8px;
            border-radius: 5px;
            margin-bottom: 5px;
            border: 1px solid #d0d5f5;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 3px;
        }

        .toolbar-variables p {
            font-size: 10px;
            color: #666;
            margin-bottom: 0;
            margin-right: 4px;
            font-weight: 600;
            white-space: nowrap;
        }

        .variable-btn {
            display: inline-block;
            padding: 3px 7px;
            margin: 0;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 10px;
            font-weight: 600;
            transition: transform 0.2s;
        }

        .variable-btn:hover {
            transform: scale(1.05);
        }

        #editorEncabezado, #editorTenor {
            height: 150px;
            background: white;
            border: 1px solid #ccc;
        }

        #editorTenor {
            height: 300px;
        }

        .preview-fondo {
            width: 100%;
            border-radius: 8px;
            margin-bottom: 15px;
            border: 2px solid #e0e0e0;
        }

        .preview-info {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 13px;
            color: #666;
        }

        .preview-info strong {
            color: #333;
        }

        .botones-accion {
            background: white;
            padding: 10px 14px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            display: flex;
            gap: 10px;
            justify-content: space-between;
        }

        .btn {
            padding: 7px 20px;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            transform: scale(1.05);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-secondary {
            background: #e0e0e0;
            color: #333;
        }

        .btn-success {
            background: #4CAF50;
            color: white;
        }

        .botones-accion-right {
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
        }

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

        .action-btn:hover {
            transform: translateY(-1px);
            filter: brightness(1.1);
            color: #fff;
        }

        .action-cancel {
            min-width: 108px;
            background: #6b7280;
            color: #fff;
        }

        .action-preview {
            background: #0891b2;
            color: #fff;
        }

        .action-save {
            background: #16a34a;
            color: #fff;
        }

        #modalPrevisualizacionPdf .modal-dialog {
            margin-top: 92px;
            margin-bottom: 16px;
            max-width: min(1240px, calc(100vw - 28px));
        }

        #modalPrevisualizacionPdf .modal-content {
            max-height: calc(100vh - 92px);
        }

        #modalPrevisualizacionPdf .modal-body {
            overflow: hidden;
        }

        #modalPrevisualizacionPdf #iframePrevisualizacionPdf {
            height: calc(100vh - 170px) !important;
        }

        @media (max-width: 768px) {
            .action-btn {
                min-width: 148px;
                font-size: 12px;
                padding: 7px 14px;
            }

            .action-cancel {
                min-width: 112px;
            }
        }

        .fondo-actual {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .fondo-actual h4 {
            color: #333;
            margin-bottom: 10px;
        }

        /* Estilos para Quill */
        .ql-toolbar {
            background: white;
            border: 1px solid #ccc;
            border-bottom: none;
            border-radius: 8px 8px 0 0;
        }

        .ql-container {
            border-radius: 0 0 8px 8px;
            font-family: 'Roboto', sans-serif;
        }

        .alert {
            padding: 7px 14px;
            border-radius: 6px;
            margin-bottom: 10px;
            font-size: 12px;
        }

        .alert-info {
            background: #e3f2fd;
            color: #1976d2;
            border-left: 4px solid #1976d2;
        }

        /* Tamaños del editor Quill */
        .ql-editor {
            font-size: 16px;
        }

        /* Fuentes aplicadas en el editor (para previsualización) */
        .ql-editor [style*="font-family: Roboto Slab"],
        .ql-editor [style*='font-family: "Roboto Slab"'],
        .ql-editor [style*="font-family: &quot;Roboto Slab&quot;"] {
            font-family: 'Roboto Slab', serif !important;
        }
        .ql-editor [style*="font-family: Great Vibes"],
        .ql-editor [style*='font-family: "Great Vibes"'],
        .ql-editor [style*="font-family: &quot;Great Vibes&quot;"] {
            font-family: 'Great Vibes', cursive !important;
        }
        .ql-editor [style*="font-family: Roboto;"],
        .ql-editor [style*='font-family: "Roboto"'] {
            font-family: 'Roboto', sans-serif !important;
        }

        .ql-editor .ql-size-large {
            font-size: 20px;
        }

        .ql-editor .ql-size-huge {
            font-size: 24px;
        }

        /* Labels del desplegable de tamaños (StyleAttributor) */
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

        /* Labels del desplegable de fuentes (especificidad alta para superar quill.snow.css) */
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
    {{-- Top Bar --}}
    <div class="top-bar">
        <div class="logo-section">
            <div class="logo-icon">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <div>
                <div class="brand-text">Certificaciones Digitales</div>
                <div class="brand-sub">Diseñador de Certificado</div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="header">
            <h1><i class="bi bi-palette-fill"></i> Diseñar Certificado</h1>
            <div class="evento-info">
                <span><strong>Evento:</strong> {{ $evento->evento }}</span>
                <span><strong>Fecha:</strong> {{ $evento->fecha_inicio }} - {{ $evento->fecha_fin }}</span>
                <span><strong>Fondo:</strong> {{ $evento->fondo->nombre }}</span>
            </div>
        </div>

        <div class="alert alert-info">
            <strong>💡 Instrucciones:</strong> Usa los botones de variables para insertar datos dinámicos. El texto que escribas aparecerá en cada certificado, mientras que las variables se reemplazarán con la información de cada persona.
        </div>

        <form action="{{ route('eventos.guardarDiseno', $evento->id) }}" method="POST" id="formDiseno">
            @csrf

            <div class="main-grid">
                <!-- Panel Editor -->
                <div class="editor-panel">
                    <!-- ENCABEZADO -->
                    <div class="editor-wrapper">
                        <label class="editor-label">📝 Encabezado (Entre los logos)</label>
                        <div class="toolbar-variables">
                            <p>📌 Variables:</p>
                            <button type="button" class="variable-btn" onclick="insertarVariable('encabezado', '@{{evento}}')">
                                📅 Evento
                            </button>
                            <button type="button" class="variable-btn" onclick="insertarVariable('encabezado', '@{{detalle}}')">
                                📄 Detalle
                            </button>
                        </div>
                        <div id="editorEncabezado"></div>
                    </div>

                    <!-- TENOR -->
                    <div class="editor-wrapper">
                        <label class="editor-label">📜 Tenor (Cuerpo del certificado)</label>
                        <div class="toolbar-variables">
                            <p>📌 Variables:</p>
                            <button type="button" class="variable-btn" onclick="insertarVariable('tenor', '@{{grado}}')">
                                🎓 Grado
                            </button>
                            <button type="button" class="variable-btn" onclick="insertarVariable('tenor', '@{{nombre}}')">
                                👤 Nombre
                            </button>
                            <button type="button" class="variable-btn" onclick="insertarVariable('tenor', '@{{paterno}}')">
                                Ap. Paterno
                            </button>
                            <button type="button" class="variable-btn" onclick="insertarVariable('tenor', '@{{materno}}')">
                                Ap. Materno
                            </button>
                            <button type="button" class="variable-btn" onclick="insertarVariable('tenor', '@{{tipo}}')">
                                🏷️ Tipo
                            </button>
                            <button type="button" class="variable-btn" onclick="insertarVariable('tenor', '@{{evento}}')">
                                📅 Evento
                            </button>
                            <button type="button" class="variable-btn" onclick="insertarVariable('tenor', '@{{fecha_inicio}}')">
                                📆 F.Inicio
                            </button>
                            <button type="button" class="variable-btn" onclick="insertarVariable('tenor', '@{{fecha_fin}}')">
                                📆 F.Fin
                            </button>
                        </div>
                        <div id="editorTenor"></div>
                    </div>

                    <input type="hidden" name="encabezado_html" id="encabezado_html">
                    <input type="hidden" name="tenor_html" id="tenor_html">
                </div>

                <div class="preview-panel">
                    <div class="section-title" style="font-size:0.85rem;">👁️ Vista Previa</div>
                    <div class="fondo-actual" style="padding:6px 8px; margin-bottom:8px;">
                        <h4 style="font-size:0.85rem; margin-bottom:2px;">{{ $evento->fondo->nombre }}</h4>
                        <span style="font-size: 11px; color: #666;">
                            {{ $evento->fondo->orientacion == 'portrait' ? '📄 Vertical' : '📃 Horizontal' }}
                        </span>
                    </div>
                    <img src="{{ asset($evento->fondo->ruta_fondo) }}"
                         alt="Fondo seleccionado"
                         class="preview-fondo">
                    <div class="preview-info" style="font-size:11px; padding:6px 8px; margin-bottom:8px;">
                        <strong>📍 Fondo:</strong> {{ $evento->fondo->descripcion }}
                    </div>
                    <button type="button" class="btn btn-secondary" style="width: 100%; font-size:11px; padding:5px;"
                            onclick="window.location.href='{{ route('eventos.seleccionarFondo', $evento->id) }}'">
                        🔄 Cambiar Fondo
                    </button>
                </div>
            </div>

            <div class="botones-accion">
                <div>
                    <a href="{{ route('eventos.show', $evento->id) }}" class="action-btn action-cancel">
                        <i class="bi bi-arrow-left"></i> Cancelar
                    </a>
                </div>
                <div class="botones-accion-right">
                    <button type="button" id="btnPrevisualizarDiseno" class="action-btn action-preview">
                        <i class="bi bi-eye"></i> Previsualización
                    </button>
                    <button type="submit" class="action-btn action-save">
                        <i class="bi bi-save"></i> Guardar Diseño
                    </button>
                </div>
            </div>
        </form>

        <form id="formPrevisualizacionPdf"
              action="{{ route('eventos.previsualizarDiseno', $evento->id) }}"
              method="POST"
              target="iframePrevisualizacionPdf"
              class="d-none">
            @csrf
            <input type="hidden" name="encabezado_html" id="preview_encabezado_html">
            <input type="hidden" name="tenor_html" id="preview_tenor_html">
        </form>

        <div class="modal fade" id="modalPrevisualizacionPdf" tabindex="-1" aria-labelledby="modalPrevisualizacionPdfLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content" style="border-radius:14px; overflow:hidden;">
                    <div class="modal-header py-2 px-3">
                        <h5 class="modal-title mb-0" id="modalPrevisualizacionPdfLabel">Previsualización del diseño</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body p-2" style="background:#f4f6fb;">
                        <iframe
                            id="iframePrevisualizacionPdf"
                            name="iframePrevisualizacionPdf"
                            title="Previsualización del certificado"
                            style="width:100%; height:78vh; border:0; border-radius:10px; background:#fff;"
                            loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        // Usar StyleAttributor para fuentes y tamaños (genera inline styles, persisten en reload)
        var FontStyle = Quill.import('attributors/style/font');
        FontStyle.whitelist = ['Roboto', 'Roboto Slab', 'Great Vibes'];
        Quill.register(FontStyle, true);

        var SizeStyle = Quill.import('attributors/style/size');
        SizeStyle.whitelist = ['13px', '14px', '15px', '16px', '18px', '20px', '25px', '30px'];
        Quill.register(SizeStyle, true);

        // Configurar editores Quill
        var quillEncabezado = new Quill('#editorEncabezado', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'font': ['Roboto', 'Roboto Slab', 'Great Vibes'] }],
                    [{ 'size': ['13px', '14px', '15px', '16px', '18px', '20px', '25px', '30px'] }],
                    ['bold', 'italic', 'underline'],
                    [{ 'align': [] }],
                    [{ 'color': [] }],
                    ['clean']
                ]
            }
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

        // Plantilla genérica encabezado (igual para todos los fondos)
        var plantillaEncabezado = '<p class="ql-align-center"><span style="color: rgb(0, 41, 102); font-family: &quot;Roboto Slab&quot;; font-size: 20px;">UNIVERSIDAD MAYOR DE SAN ANDRÉS</span></p><p class="ql-align-center"><span style="color: rgb(0, 41, 102); font-family: &quot;Roboto Slab&quot;; font-size: 14px;">FACULTAD DE MEDICINA, ENFERMERÍA, NUTRICIÓN Y TECNOLOGÍA MÉDICA</span></p><p class="ql-align-center"><span style="color: rgb(0, 41, 102); font-family: &quot;Roboto Slab&quot;; font-size: 14px;">INSTITUTO DE INVESTIGACIÓN EN SALUD Y DESARROLLO IINSAD</span></p><p class="ql-align-center"><span style="font-size: 14px; font-family: &quot;Roboto Slab&quot;;">VICEDECANATO</span></p><p><br></p>';

        @if($evento->fondo->orientacion === 'portrait')
        {{-- Plantilla tenor para fondos verticales (1, 2, 3) --}}
        var plantillaTenor = '<p class="ql-align-justify"><span style="font-size: 18px; font-family: &quot;Roboto Slab&quot;;">Se confiere el presente:</span></p><p class="ql-align-justify"><br></p><p class="ql-align-center"><strong style="font-size: 20px; font-family: &quot;Roboto Slab&quot;;">CERTIFICADO</strong></p><p class="ql-align-center"><br></p><p class="ql-align-center"><span style="font-size: 30px; font-family: &quot;Great Vibes&quot;;">A: @{{grado}}</span><span style="font-size: 30px; font-family: &quot;Great Vibes&quot;;"> </span><span style="font-size: 30px; font-family: &quot;Great Vibes&quot;;">@{{nombre}}</span><span style="font-size: 30px; font-family: &quot;Great Vibes&quot;;"> </span><span style="font-size: 30px; font-family: &quot;Great Vibes&quot;;">@{{paterno}} @{{materno}}</span></p><p class="ql-align-center"><br></p><p class="ql-align-justify"><span style="font-size: 18px; font-family: &quot;Roboto Slab&quot;;">Por su participación en calidad de:</span></p><p class="ql-align-justify"><br></p><p class="ql-align-center"><strong style="font-size: 20px; font-family: &quot;Roboto Slab&quot;;">@{{tipo}}</strong></p><p class="ql-align-center"><br></p><p class="ql-align-justify"><span style="font-size: 18px; font-family: &quot;Roboto Slab&quot;;">En la "@{{evento}}", realizada de manera virtual con ponencias nacionales e internacionales, los días 27 y 28 de noviembre de 2025 con una carga horaria de 10 horas académicas.</span></p><p><span style="font-size: 18px; font-family: &quot;Roboto Slab&quot;;">La Paz, febrero de 2026.</span></p>';
        @else
        {{-- Plantilla tenor para fondos horizontales (4, 5, 6) --}}
        var plantillaTenor = '<p class="ql-align-justify"><span style="font-size: 18px; font-family: &quot;Roboto Slab&quot;;">Se confiere el presente:</span></p><p class="ql-align-justify"><br></p><p class="ql-align-center"><strong style="font-size: 20px; font-family: &quot;Roboto Slab&quot;;">CERTIFICADO</strong></p><p class="ql-align-center"><br></p><p class="ql-align-center"><span style="font-size: 30px; font-family: &quot;Great Vibes&quot;;">A: @{{grado}}</span><span style="font-size: 30px; font-family: &quot;Great Vibes&quot;;"> </span><span style="font-size: 30px; font-family: &quot;Great Vibes&quot;;">@{{nombre}}</span><span style="font-size: 30px; font-family: &quot;Great Vibes&quot;;"> </span><span style="font-size: 30px; font-family: &quot;Great Vibes&quot;;">@{{paterno}} @{{materno}}</span></p><p class="ql-align-center"><br></p><p class="ql-align-justify"><span style="font-size: 18px; font-family: &quot;Roboto Slab&quot;;">Por su participación en calidad de:</span></p><p class="ql-align-justify"><br></p><p class="ql-align-center"><strong style="font-size: 20px; font-family: &quot;Roboto Slab&quot;;">@{{tipo}}</strong></p><p class="ql-align-center"><br></p><p class="ql-align-justify"><span style="font-size: 18px; font-family: &quot;Roboto Slab&quot;;">En la "@{{evento}}", realizada de manera virtual con ponencias nacionales e internacionales, los días 27 y 28 de noviembre de 2025 con una carga horaria de 10 horas académicas.</span></p><p><span style="font-size: 18px; font-family: &quot;Roboto Slab&quot;;">La Paz, febrero de 2026.</span></p>';
        @endif

        // Cargar contenido existente o plantilla por defecto
        // Se considera "sin contenido" cuando es null, vacío o solo tiene <p><br></p>
        @php
            $encabezadoVacio = empty(trim(strip_tags($evento->encabezado_html ?? '')));
            $tenorVacio      = empty(trim(strip_tags($evento->tenor_html ?? '')));
        @endphp

        setTimeout(function() {
            @if(!$encabezadoVacio)
                quillEncabezado.root.innerHTML = {!! json_encode($evento->encabezado_html) !!};
            @else
                quillEncabezado.root.innerHTML = plantillaEncabezado;
            @endif
            quillEncabezado.history.clear();

            @if(!$tenorVacio)
                quillTenor.root.innerHTML = {!! json_encode($evento->tenor_html) !!};
            @else
                quillTenor.root.innerHTML = plantillaTenor;
            @endif
            quillTenor.history.clear();
        }, 50);

        // Función para insertar variables
        function insertarVariable(editor, variable) {
            if (editor === 'encabezado') {
                var range = quillEncabezado.getSelection(true);
                quillEncabezado.insertText(range.index, variable);
                quillEncabezado.setSelection(range.index + variable.length);
            } else {
                var range = quillTenor.getSelection(true);
                quillTenor.insertText(range.index, variable);
                quillTenor.setSelection(range.index + variable.length);
            }
        }

        // Guardar contenido HTML antes de enviar
        document.getElementById('formDiseno').addEventListener('submit', function(e) {
            document.getElementById('encabezado_html').value = quillEncabezado.root.innerHTML;
            document.getElementById('tenor_html').value = quillTenor.root.innerHTML;
        });

        var btnPrevisualizarDiseno = document.getElementById('btnPrevisualizarDiseno');
        var modalPrevisualizacionPdf = document.getElementById('modalPrevisualizacionPdf');
        var formPrevisualizacionPdf = document.getElementById('formPrevisualizacionPdf');
        var previewEncabezadoHtml = document.getElementById('preview_encabezado_html');
        var previewTenorHtml = document.getElementById('preview_tenor_html');

        if (btnPrevisualizarDiseno && formPrevisualizacionPdf) {
            btnPrevisualizarDiseno.addEventListener('click', function() {
                if (previewEncabezadoHtml) {
                    previewEncabezadoHtml.value = quillEncabezado.root.innerHTML;
                }
                if (previewTenorHtml) {
                    previewTenorHtml.value = quillTenor.root.innerHTML;
                }

                if (window.bootstrap && modalPrevisualizacionPdf) {
                    bootstrap.Modal.getOrCreateInstance(modalPrevisualizacionPdf).show();
                }

                formPrevisualizacionPdf.submit();
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

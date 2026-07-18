<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva fotografía</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #0e0d0c;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        .panel {
            max-width: 520px;
            width: 100%;
            background: #1a1816;
            border-radius: 16px;
            padding: 2rem;
        }

        .panel-title {
            font-family: Georgia, "Times New Roman", serif;
            font-size: 26px;
            color: #f4ede4;
            margin: 0 0 4px;
        }

        .panel-subtitle {
            font-size: 13px;
            color: #9c948a;
            margin: 0 0 24px;
        }

        .dropzone {
            border: 1px dashed #4a443d;
            border-radius: 10px;
            padding: 28px;
            text-align: center;
            cursor: pointer;
            position: relative;
            transition: border-color .2s;
        }

        .dropzone.dragging {
            border-color: #d85a30;
        }

        .dropzone input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
        }

        .dropzone-empty i {
            font-size: 32px;
            color: #d85a30;
        }

        .dropzone-empty p {
            margin: 12px 0 4px;
            color: #f4ede4;
            font-size: 14px;
        }

        .dropzone-empty span {
            color: #7a736a;
            font-size: 12px;
        }

        .preview-frame {
            display: inline-block;
            border: 6px solid #2b2622;
            border-radius: 6px;
            background: #000;
        }

        .preview-frame img {
            display: block;
            max-width: 100%;
            max-height: 220px;
            border-radius: 2px;
        }

        .file-name {
            color: #c9c1b6;
            font-size: 12px;
            margin: 10px 0 0;
        }

        .form-label-custom {
            font-size: 12px;
            color: #9c948a;
            margin-bottom: 6px;
            display: block;
        }

        .form-control-custom {
            width: 100%;
            border-radius: 8px;
            border: 1px solid #3a342e;
            background: #241f1a;
            color: #f4ede4;
            padding: 10px 12px;
            font-size: 14px;
        }

        .form-control-custom:focus {
            outline: none;
            border-color: #d85a30;
            background: #241f1a;
            color: #f4ede4;
            box-shadow: none;
        }

        .form-control-custom::placeholder {
            color: #6b6459;
        }

        .visibilidad-toggle {
            display: flex;
            background: #241f1a;
            border-radius: 20px;
            padding: 3px;
            border: 1px solid #3a342e;
        }

        .visibilidad-toggle label {
            padding: 5px 14px;
            border-radius: 16px;
            font-size: 12px;
            cursor: pointer;
            color: #9c948a;
            margin: 0;
            transition: background .15s, color .15s;
        }

        .visibilidad-toggle input {
            display: none;
        }

        .visibilidad-toggle input:checked + label {
            background: #d85a30;
            color: #1a1816;
        }

        .btn-publicar {
            margin-top: 24px;
            width: 100%;
            height: 42px;
            border-radius: 8px;
            border: none;
            background: #d85a30;
            color: #1a1816;
            font-size: 14px;
            font-weight: 600;
        }
        .btn-inicio{
            margin-top: 24px;
            width: 100%;
            height: 42px;
            border-radius: 8px;
            border: none;
            background: #d85a30;
            color: #1a1816;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-publicar:hover {
            background: #c44f29;
            color: #1a1816;
        }

        .alert-exito {
            background: #243b2e;
            border: 1px solid #3a6b4f;
            color: #bfe8cf;
            font-size: 13px;
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 18px;
        }

        .error-text {
            color: #e9897a;
            font-size: 12px;
            margin: 6px 0 0;
        }
    </style>
</head>
<body>

    <div class="panel">
        <p class="panel-title">Nueva fotografía</p>
        <p class="panel-subtitle">Sube una imagen para la página principal.</p>

        @if (session('exito'))
            <div class="alert-exito">{{ session('exito') }}</div>
        @endif

        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="formSubida">
            @csrf

            {{-- Zona de arrastrar / soltar --}}
            <div class="dropzone" id="dropzone">
                <input type="file" name="imagen" id="fileInput" accept="image/*">

                <div class="dropzone-empty" id="emptyState">
                    <i class="bi bi-image"></i>
                    <p>Arrastra una imagen o haz clic para elegirla</p>
                    <span>JPG o PNG, máx. 8 MB</span>
                </div>

                <div id="previewState" style="display:none;">
                    <div class="preview-frame">
                        <img id="imgPreview" src="" alt="Vista previa">
                    </div>
                    <p class="file-name" id="fileName"></p>
                </div>
            </div>
            @error('imagen')
                <p class="error-text">{{ $message }}</p>
            @enderror

            {{-- Título --}}
            <div class="mt-4">
                <label class="form-label-custom">Título</label>
                <input type="text" name="titulo" class="form-control-custom" placeholder="Amanecer en la costa" value="{{ old('titulo') }}">
                @error('titulo')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>

            {{-- Descripción --}}
            <div class="mt-3">
                <label class="form-label-custom">Descripción</label>
                <textarea name="descripcion" class="form-control-custom" rows="3" placeholder="Tomada al amanecer, exposición larga...">{{ old('descripcion') }}</textarea>
            </div>

            {{-- Visibilidad --}}
            <div class="mt-4 d-flex align-items-center justify-content-between">
                <label class="form-label-custom mb-0">Visibilidad</label>
                <div class="visibilidad-toggle">
                    <input type="radio" name="estado" id="estadoPublico" value="publicado" checked>
                    <label for="estadoPublico">Pública</label>

                    <input type="radio" name="estado" id="estadoBorrador" value="borrador">
                    <label for="estadoBorrador">Borrador</label>
                </div>
            </div>

            <button type="submit" class="btn-publicar">
                <i class="bi bi-upload me-2"></i>Publicar fotografía
            </button>

            <button type="button" class="btn-publicar">
                <li><a href="{{ route('home') }}" class="active">Inicio</a></li>
            </button>
        </form>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const dz = document.getElementById('dropzone');
        const fileInput = document.getElementById('fileInput');
        const emptyState = document.getElementById('emptyState');
        const previewState = document.getElementById('previewState');
        const imgPreview = document.getElementById('imgPreview');
        const fileName = document.getElementById('fileName');

        function handleFile(file) {
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                imgPreview.src = e.target.result;
                fileName.textContent = file.name;
                emptyState.style.display = 'none';
                previewState.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }

        fileInput.addEventListener('change', (e) => handleFile(e.target.files[0]));

        dz.addEventListener('dragover', (e) => {
            e.preventDefault();
            dz.classList.add('dragging');
        });

        dz.addEventListener('dragleave', () => {
            dz.classList.remove('dragging');
        });

        dz.addEventListener('drop', (e) => {
            e.preventDefault();
            dz.classList.remove('dragging');
            const file = e.dataTransfer.files[0];
            if (file) {
                fileInput.files = e.dataTransfer.files;
                handleFile(file);
            }
        });
    </script>
</body>
</html>
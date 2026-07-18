<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio — Mi Galería</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cloudflare.com">

    <style>
        /* ─── RESET Y BASE ─────────────────────────────── */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: #0e0d0c;
            color: #f4ede4;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ─── NAVBAR ────────────────────────────────────── */
        .navbar-custom {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            padding: 20px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(to bottom, rgba(14, 13, 12, 0.92) 0%, transparent 100%);
            backdrop-filter: blur(0px);
            transition: background .3s, padding .3s;
        }

        .navbar-custom.scrolled {
            background: rgba(14, 13, 12, 0.96);
            backdrop-filter: blur(12px);
            padding: 14px 40px;
            border-bottom: 1px solid #2a2420;
        }

        .navbar-brand-custom {
            font-family: Georgia, "Times New Roman", serif;
            font-size: 20px;
            color: #f4ede4;
            text-decoration: none;
            letter-spacing: 0.5px;
        }

        .navbar-brand-custom span {
            color: #d85a30;
        }

        .navbar-nav-custom {
            display: flex;
            align-items: center;
            gap: 32px;
            list-style: none;
        }

        .navbar-nav-custom a {
            color: #9c948a;
            text-decoration: none;
            font-size: 13px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            transition: color .2s;
            position: relative;
        }

        .navbar-nav-custom a::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 0;
            height: 1px;
            background: #d85a30;
            transition: width .2s;
        }

        .navbar-nav-custom a:hover,
        .navbar-nav-custom a.active {
            color: #f4ede4;
        }

        .navbar-nav-custom a:hover::after,
        .navbar-nav-custom a.active::after {
            width: 100%;
        }

        /* ─── SLIDESHOW ─────────────────────────────────── */
        .slideshow {
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            background: #0e0d0c;
        }

        .slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 1.2s cubic-bezier(0.4, 0, 0.2, 1);
            pointer-events: none;
        }

        .slide.active {
            opacity: 1;
            pointer-events: auto;
        }

        /* Imagen de fondo del slide */
        .slide-bg {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            transform: scale(1.02);
            transition: transform 8s ease-out;
            filter: brightness(0.75);
        }

        .slide.active .slide-bg {
            transform: scale(1);
        }

        /* Overlay degradado inferior */
        .slide-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top,
                    rgba(14, 13, 12, 0.92) 0%,
                    rgba(14, 13, 12, 0.4) 40%,
                    transparent 70%);
        }

        /* Contenido textual del slide */
        .slide-content {
            position: absolute;
            bottom: 120px;
            left: 60px;
            right: 60px;
            transform: translateY(20px);
            opacity: 0;
            transition: transform .8s .4s ease-out, opacity .8s .4s ease-out;
        }

        .slide.active .slide-content {
            transform: translateY(0);
            opacity: 1;
        }

        .slide-eyebrow {
            font-size: 11px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #d85a30;
            margin-bottom: 10px;
        }

        .slide-title {
            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(28px, 5vw, 56px);
            color: #f4ede4;
            line-height: 1.15;
            max-width: 600px;
            margin-bottom: 12px;
        }

        .slide-desc {
            font-size: 14px;
            color: #c9c1b6;
            max-width: 420px;
            line-height: 1.7;
        }

        /* Slide vacío (sin fotos en DB) */
        .slide-empty {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 16px;
            color: #4a443d;
        }

        .slide-empty i {
            font-size: 48px;
        }

        .slide-empty p {
            font-size: 14px;
        }

        /* ─── CONTROLES ─────────────────────────────────── */
        .slide-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            background: rgba(26, 24, 22, 0.6);
            border: 1px solid #3a342e;
            color: #f4ede4;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background .2s, border-color .2s;
            font-size: 18px;
        }

        .slide-arrow:hover {
            background: #d85a30;
            border-color: #d85a30;
        }

        .slide-arrow.prev {
            left: 24px;
        }

        .slide-arrow.next {
            right: 24px;
        }

        /* ─── PROGRESS BARS (indicadores) ───────────────── */
        .slide-progress {
            position: absolute;
            bottom: 60px;
            left: 60px;
            display: flex;
            gap: 6px;
            z-index: 10;
        }

        .progress-bar-item {
            width: 40px;
            height: 2px;
            background: rgba(244, 237, 228, 0.25);
            border-radius: 2px;
            overflow: hidden;
            cursor: pointer;
        }

        .progress-bar-item .fill {
            height: 100%;
            width: 0%;
            background: #d85a30;
            border-radius: 2px;
            transition: width linear;
        }

        .progress-bar-item.active .fill {
            width: 100%;
        }

        .progress-bar-item.done .fill {
            width: 100%;
            background: rgba(244, 237, 228, 0.6);
            transition: none;
        }

        /* ─── CONTADOR ───────────────────────────────────── */
        .slide-counter {
            position: absolute;
            bottom: 64px;
            right: 60px;
            font-size: 12px;
            color: #6b6459;
            letter-spacing: 2px;
            z-index: 10;
        }

        .slide-counter span {
            color: #f4ede4;
            font-size: 16px;
        }

        /* ─── SECCIÓN INFERIOR ───────────────────────────── */
        .section-info {
            padding: 80px 60px;
            max-width: 900px;
            margin: 0 auto;
            text-align: center;
        }

        .section-info h2 {
            font-family: Georgia, "Times New Roman", serif;
            font-size: 32px;
            color: #f4ede4;
            margin-bottom: 16px;
        }

        .section-info p {
            font-size: 15px;
            color: #9c948a;
            line-height: 1.8;
        }

        .btn-subir {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 32px;
            padding: 10px 24px;
            border-radius: 8px;
            border: 1px solid #d85a30;
            color: #d85a30;
            font-size: 13px;
            letter-spacing: 1px;
            text-transform: uppercase;
            text-decoration: none;
            transition: background .2s, color .2s;
        }

        .btn-subir:hover {
            background: #d85a30;
            color: #1a1816;
        }

        footer {
            border-top: 1px solid #2a2420;
            padding: 24px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        footer p {
            font-size: 12px;
            color: #4a443d;
        }

        footer a {
            color: #4a443d;
            text-decoration: none;
            transition: color .2s;
            margin-right: 800px;
        }

        @media (max-width: 600px) {
            .navbar-custom {
                padding: 16px 20px;
            }

            .slide-content {
                left: 24px;
                right: 24px;
                bottom: 100px;
            }

            .slide-progress {
                left: 24px;
                bottom: 50px;
            }

            .slide-counter {
                right: 24px;
                bottom: 54px;
            }

            .slide-arrow.prev {
                left: 12px;
            }

            .slide-arrow.next {
                right: 12px;
            }

            .section-info {
                padding: 60px 24px;
            }

            footer {
                flex-direction: column;
                gap: 8px;
                padding: 24px;
            }
        }

        /* ─── LIGHTBOX ──────────────────────────────────── */
        .lightbox {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 999;
            background: rgba(10, 9, 8, 0.96);
            align-items: center;
            justify-content: center;
            cursor: zoom-out;
        }

        .lightbox.open {
            display: flex;
        }

        .lightbox img {
            max-width: 92vw;
            max-height: 88vh;
            object-fit: contain;
            border-radius: 4px;
            box-shadow: 0 0 80px rgba(0, 0, 0, 0.8);
            cursor: default;
            animation: lbIn .25s ease-out;
        }

        .lightbox-caption {
            position: absolute;
            bottom: 32px;
            left: 0;
            right: 0;
            text-align: center;
            font-family: Georgia, serif;
            font-size: 16px;
            color: #c9c1b6;
            pointer-events: none;
        }

        .lightbox-close {
            position: absolute;
            top: 20px;
            right: 24px;
            font-size: 28px;
            color: #6b6459;
            cursor: pointer;
            transition: color .2s;
            line-height: 1;
            background: none;
            border: none;
        }

        .lightbox-close:hover {
            color: #f4ede4;
        }

        @keyframes lbIn {
            from {
                opacity: 0;
                transform: scale(0.96);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Cursor pointer sobre la imagen del slide para indicar que es clicable */
        .slide-bg {
            cursor: pointer;
        }

        /* ─── LIKE BUTTON ───────────────────────────────── */
        .like-btn {
            position: absolute;
            bottom: 120px;
            right: 60px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            z-index: 10;
            transition: transform .15s;
        }

        .like-btn:hover {
            transform: scale(1.1);
        }

        .like-btn:active {
            transform: scale(0.92);
        }

        .heart-icon {
            width: 28px;
            height: 28px;
            fill: rgba(255, 255, 255, 0.3);
            stroke: rgba(255, 255, 255, 0.8);
            stroke-width: 1px;
            transition: fill .2s, stroke .2s, transform .3s cubic-bezier(0.17, 0.89, 0.32, 1.49);
            filter: drop-shadow(0 1px 4px rgba(0, 0, 0, 0.5));
        }

        .like-btn.liked .heart-icon {
            fill: #ed4956;
            stroke: #ed4956;
            transform: scale(1.2);
        }

        .like-btn:hover:not(.liked) .heart-icon {
            fill: rgba(237, 73, 86, 0.4);
            stroke: #ed4956;
        }

        .like-count {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.75);
            line-height: 1;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.6);
        }

        @keyframes heartPop {
            0% {
                transform: scale(1);
            }

            40% {
                transform: scale(1.5);
            }

            70% {
                transform: scale(0.9);
            }

            100% {
                transform: scale(1.2);
            }
        }

        .like-btn.pop .heart-icon {
            animation: heartPop .4s cubic-bezier(0.17, 0.89, 0.32, 1.49) forwards;
        }

        @media (max-width: 600px) {
            .like-btn {
                bottom: 100px;
                right: 24px;
            }
        }
    </style>
</head>

<body>

    {{-- ─── NAVBAR ──────────────────────────────────────── --}}
    <nav class="navbar-custom" id="navbar">
        <a href="{{ route('home') }}" class="navbar-brand-custom">Braulio Toscano<span>.</span></a>
        <ul class="navbar-nav-custom">
            <li><a href="{{ route('home') }}" class="active">Inicio</a></li>

        </ul>
    </nav>

    {{-- ─── SLIDESHOW ────────────────────────────────────── --}}
    <div class="slideshow" id="slideshow">

        @if ($posts->isEmpty())
            <div class="slide-empty">
                <i class="bi bi-images"></i>
                <p>Aún no hay fotografías. <a href="{{ route('posts.create') }}" style="color:#d85a30;">Sube la
                        primera.</a></p>
            </div>
        @else
            @foreach ($posts as $index => $post)
                <div class="slide {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}">
                    <div class="slide-bg" style="background-image: url('{{ asset('storage/' . $post->imagen) }}')">
                    </div>
                    <div class="slide-overlay"></div>
                    <div class="slide-content">
                        <p class="slide-eyebrow">Fotografía {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</p>
                        <h1 class="slide-title">{{ $post->titulo }}</h1>
                        @if ($post->descripcion)
                            <p class="slide-desc">{{ Str::limit($post->descripcion, 120) }}</p>
                        @endif
                    </div>
                </div>
                {{-- Botón like --}}
                <button class="like-btn {{ in_array($post->id, $likedIds) ? 'liked' : '' }}"
                    data-post-id="{{ $post->id }}" data-url="{{ route('posts.like', $post) }}"
                    aria-label="Me gusta">
                    <svg class="heart-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 21.593c-5.63-5.539-11-10.297-11-14.402
                 C1 3.145 3.23 1 6.084 1c1.86 0 3.63.98 5.916 3.171
                 C14.272 1.979 16.042 1 17.916 1
                 20.77 1 23 3.145 23 7.191
                 c0 4.104-5.369 8.862-11 14.402z" />
                    </svg>
                    <span class="like-count">{{ $post->likes_count }}</span>
                </button>
            @endforeach

            {{-- Flechas --}}
            <button class="slide-arrow prev" id="prevBtn" aria-label="Anterior">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button class="slide-arrow next" id="nextBtn" aria-label="Siguiente">
                <i class="bi bi-chevron-right"></i>
            </button>

            {{-- Progress bars --}}
            <div class="slide-progress" id="progressBars">
                @foreach ($posts as $index => $post)
                    <div class="progress-bar-item {{ $index === 0 ? 'active' : '' }}"
                        data-index="{{ $index }}">
                        <div class="fill"></div>
                    </div>
                @endforeach
            </div>

            {{-- Contador --}}
            <div class="slide-counter">
                <span id="currentNum">01</span> / {{ str_pad($posts->count(), 2, '0', STR_PAD_LEFT) }}
            </div>

        @endif
    </div>

    {{-- ─── SECCIÓN INFERIOR ────────────────────────────── --}}
    <div class="section-info">
        <h2>Galería fotográfica</h2>
        <p>Una colección de momentos capturados con intención.<br>Cada imagen, una historia.</p>

    </div>

    <footer>
        <p>© {{ date('Y') }} Braulio. Todos los derechos reservados.</p>

        <div style="display:flex;gap:16px;align-items:center;">
            <a href="https://www.instagram.com/brau_fxt/" target="_blank" rel="noopener noreferrer"
                style="color:#4a443d;font-size:20px;transition:color .2s;" onmouseover="this.style.color='#d85a30'"
                onmouseout="this.style.color='#4a443d'">
                <i class="bi bi-instagram"> - brau_fxt</i>
            </a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ─── NAVBAR SCROLL ────────────────────────────────
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 30);
        });

        // ─── SLIDESHOW ────────────────────────────────────
        const slides = document.querySelectorAll('.slide');
        const bars = document.querySelectorAll('.progress-bar-item');
        const currentNum = document.getElementById('currentNum');

        if (slides.length > 0) {
            const DURATION = 5000;
            let current = 0;
            let timer = null;

            function goTo(index) {
                slides[current].classList.remove('active');
                bars[current].classList.remove('active');
                bars[current].classList.add('done');
                bars[current].querySelector('.fill').style.transition = 'none';

                if (index < current) {
                    bars.forEach((b, i) => {
                        if (i >= index) {
                            b.classList.remove('active', 'done');
                            b.querySelector('.fill').style.width = '0%';
                            b.querySelector('.fill').style.transition = 'none';
                        }
                    });
                }

                current = (index + slides.length) % slides.length;
                slides[current].classList.add('active');
                currentNum.textContent = String(current + 1).padStart(2, '0');

                bars.forEach((b, i) => {
                    if (i < current) {
                        b.classList.remove('active');
                        b.classList.add('done');
                        b.querySelector('.fill').style.width = '100%';
                    }
                });

                bars[current].classList.remove('done');
                bars[current].classList.add('active');

                const fill = bars[current].querySelector('.fill');
                fill.style.transition = 'none';
                fill.style.width = '0%';

                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        fill.style.transition = `width ${DURATION}ms linear`;
                        fill.style.width = '100%';
                    });
                });

                resetAutoplay();
            }

            function resetAutoplay() {
                clearInterval(timer);
                timer = setInterval(() => goTo(current + 1), DURATION);
            }

            document.getElementById('prevBtn').addEventListener('click', () => goTo(current - 1));
            document.getElementById('nextBtn').addEventListener('click', () => goTo(current + 1));
            bars.forEach((b, i) => b.addEventListener('click', () => goTo(i)));

            goTo(0);

            // ─── LIGHTBOX ─────────────────────────────────
            const lightbox = document.getElementById('lightbox');
            const lbImg = document.getElementById('lbImg');
            const lbCaption = document.getElementById('lbCaption');
            const lbClose = document.getElementById('lbClose');

            // Abre el lightbox al hacer clic en el fondo del slide activo
            document.querySelectorAll('.slide-bg').forEach((bg) => {
                bg.addEventListener('click', () => {
                    // Pausa el autoplay mientras el lightbox está abierto
                    clearInterval(timer);

                    const url = bg.style.backgroundImage.replace(/url\(["']?/, '').replace(/["']?\)/, '');
                    const slide = bg.closest('.slide');
                    const title = slide.querySelector('.slide-title')?.textContent || '';

                    lbImg.src = url;
                    lbCaption.textContent = title;
                    lightbox.classList.add('open');
                    document.body.style.overflow = 'hidden';
                });
            });

            // Cierra al hacer clic en el fondo o en la X
            lightbox.addEventListener('click', (e) => {
                if (e.target === lightbox) cerrarLightbox();
            });
            lbClose.addEventListener('click', cerrarLightbox);

            // Cierra con Escape
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') cerrarLightbox();
            });

            function cerrarLightbox() {
                lightbox.classList.remove('open');
                document.body.style.overflow = '';
                lbImg.src = '';
                // Reanuda el autoplay
                resetAutoplay();
            }
        }
        // ─── LIKES ────────────────────────────────────────
        document.querySelectorAll('.like-btn').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.stopPropagation(); // evita abrir el lightbox

                const postId = btn.dataset.postId;
                const url = btn.dataset.url;

                // Animación inmediata (optimistic UI)
                btn.classList.add('pop');
                setTimeout(() => btn.classList.remove('pop'), 400);

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                    });

                    const data = await response.json();

                    btn.classList.toggle('liked', data.liked);
                    btn.querySelector('.like-count').textContent = data.count;

                } catch (err) {
                    console.error('Error al dar like:', err);
                }
            });
        });
    </script>
    {{-- ─── LIGHTBOX ────────────────────────────────── --}}
    <div class="lightbox" id="lightbox">
        <button class="lightbox-close" id="lbClose" aria-label="Cerrar">
            <i class="bi bi-x"></i>
        </button>
        <img id="lbImg" src="" alt="">
        <p class="lightbox-caption" id="lbCaption"></p>
    </div>
</body>

</html>

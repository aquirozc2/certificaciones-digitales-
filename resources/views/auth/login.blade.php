<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar Sesión — Certificaciones Digitales</title>

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('img/logo_medicina.png') }}">

    {{-- Tailwind via Vite --}}
    @vite(['resources/css/app.css'])

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }

        /* Animación de entrada */
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up {
            animation: fadeSlideUp 0.7s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        .animate-fade-up-delay {
            animation: fadeSlideUp 0.7s cubic-bezier(0.22, 1, 0.36, 1) 0.15s both;
        }

        /* Logo pulse suave */
        @keyframes logoPulse {
            0%, 100% { filter: drop-shadow(0 0 12px rgba(255,255,255,0.08)); }
            50%      { filter: drop-shadow(0 0 28px rgba(255,255,255,0.18)); }
        }
        .logo-glow { animation: logoPulse 4s ease-in-out infinite; }

        /* Spinner de carga */
        @keyframes spin { to { transform: rotate(360deg); } }
        .btn-spinner {
            display: none;
            width: 20px; height: 20px;
            border: 2.5px solid rgba(255,255,255,0.35);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.65s linear infinite;
        }
        .is-loading .btn-spinner { display: inline-block; }
        .is-loading .btn-label  { visibility: hidden; }
    </style>
</head>
<body class="h-screen w-full overflow-hidden bg-white">

    {{-- ════════════════════════════════════════════════
         CONTENEDOR SPLIT-SCREEN
         ════════════════════════════════════════════════ --}}
    <div class="flex h-full w-full flex-col lg:flex-row">

        {{-- ───────────────────────────────────────────
             LADO IZQUIERDO — Branding Institucional
             ─────────────────────────────────────────── --}}
        <div class="relative flex w-full flex-col items-center justify-center
                    bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950
                    px-8 py-12 lg:w-1/2 lg:py-0">

            {{-- Patrón decorativo sutil --}}
            <div class="pointer-events-none absolute inset-0 opacity-[0.04]"
                 style="background-image: url(&quot;data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='1'%3E%3Ccircle cx='1' cy='1' r='1'/%3E%3C/g%3E%3C/svg%3E&quot;);">
            </div>

            {{-- Línea decorativa superior --}}
            <div class="absolute left-12 right-12 top-0 hidden h-px bg-gradient-to-r from-transparent via-white/20 to-transparent lg:block"></div>

            {{-- Contenido centrado --}}
            <div class="relative z-10 flex max-w-md flex-col items-center text-center animate-fade-up">

                {{-- Logo circular --}}
                <div class="logo-glow mb-8 flex h-44 w-44 items-center justify-center
                            rounded-full border-[3px] border-white/15 bg-white/5
                            shadow-2xl backdrop-blur-xs lg:h-52 lg:w-52">
                    <img
                        src="{{ asset('img/medicina.png') }}"
                        alt="Logo Carrera de Medicina"
                        class="h-36 w-36 rounded-full object-contain lg:h-44 lg:w-44"
                    >
                </div>

                {{-- Texto institucional --}}
                <h2 class="mb-3 text-lg font-bold tracking-wider text-white/90 uppercase lg:text-xl">
                    Carrera de Medicina
                </h2>

                <h1 class="text-2xl leading-snug font-semibold tracking-tight text-white lg:text-3xl">
                    Sistema de Certificaciones y Memorándums Digitales
                </h1>

                {{-- Separador --}}
                <div class="mt-6 h-px w-24 bg-gradient-to-r from-transparent via-white/30 to-transparent"></div>
            </div>

            {{-- Línea decorativa inferior --}}
            <div class="absolute bottom-0 left-12 right-12 hidden h-px bg-gradient-to-r from-transparent via-white/20 to-transparent lg:block"></div>
        </div>

        {{-- ───────────────────────────────────────────
             LADO DERECHO — Formulario de Login
             ─────────────────────────────────────────── --}}
        <div class="flex w-full flex-1 flex-col items-center justify-center
                    bg-white px-6 py-10 lg:w-1/2 lg:py-0">

            <div class="w-full max-w-sm animate-fade-up-delay">

                {{-- Encabezado --}}
                <h2 class="mb-1 text-3xl font-bold tracking-tight text-blue-950">
                    Iniciar Sesión
                </h2>
                <p class="mb-8 text-sm text-gray-400">
                    Ingrese sus credenciales para acceder al sistema
                </p>

                {{-- ── Mensaje de error global ── --}}
                @if (session('error'))
                    <div class="mb-5 flex items-start gap-3 rounded-lg border border-red-200
                                bg-red-50 px-4 py-3 text-sm text-red-700"
                         role="alert">
                        {{-- Icono exclamación --}}
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-400" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-5 flex items-start gap-3 rounded-lg border border-red-200
                                bg-red-50 px-4 py-3 text-sm text-red-700"
                         role="alert">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-400" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
                        </svg>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- ── Formulario ── --}}
                <form method="POST" action="{{ route('login') }}" id="loginForm" autocomplete="off">
                    @csrf

                    {{-- Campo: USUARIO --}}
                    <div class="mb-5">
                        <label for="username"
                               class="mb-2 block text-xs font-bold tracking-widest text-gray-700 uppercase">
                            Usuario
                        </label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="{{ old('username') }}"
                            placeholder="Usuario"
                            required
                            autofocus
                            class="w-full rounded-lg border border-gray-200 bg-gray-50/50
                                   px-4 py-3 text-sm text-gray-800 outline-none
                                   transition-all duration-200 placeholder:text-gray-400
                                   hover:border-gray-300
                                   focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20"
                        >
                        @error('username')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Campo: CONTRASEÑA --}}
                    <div class="mb-6">
                        <label for="password"
                               class="mb-2 block text-xs font-bold tracking-widest text-gray-700 uppercase">
                            Contraseña
                        </label>
                        <div class="relative">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Contraseña"
                                required
                                class="w-full rounded-lg border border-gray-200 bg-gray-50/50
                                       px-4 py-3 pr-11 text-sm text-gray-800 outline-none
                                       transition-all duration-200 placeholder:text-gray-400
                                       hover:border-gray-300
                                       focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20"
                            >
                            {{-- Toggle visibilidad --}}
                            <button type="button" id="togglePassword" tabindex="-1"
                                    aria-label="Mostrar contraseña"
                                    class="absolute right-3 top-1/2 -translate-y-1/2
                                           text-gray-400 transition-colors hover:text-blue-600">
                                {{-- Ojo abierto --}}
                                <svg id="eyeOpen" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                                {{-- Ojo cerrado (oculto por defecto) --}}
                                <svg id="eyeClosed" class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Botón de envío --}}
                    <button type="submit" id="btnLogin"
                            class="relative flex w-full items-center justify-center
                                   rounded-lg bg-red-500 px-6 py-3.5
                                   text-sm font-semibold tracking-wide text-white
                                   shadow-md shadow-red-500/25
                                   transition-all duration-200
                                   hover:bg-red-600 hover:shadow-lg hover:shadow-red-500/30
                                   focus:outline-none focus:ring-2 focus:ring-red-500/40
                                   active:scale-[0.98]">
                        <span class="btn-label">Iniciar Sesión</span>
                        <span class="btn-spinner absolute"></span>
                    </button>
                </form>

                {{-- Enlace: Olvidé mi contraseña --}}
                <p class="mt-5 text-center">
                    <a href="#" class="text-sm text-gray-400 transition-colors
                                      hover:text-blue-600 hover:underline hover:underline-offset-4">
                        Olvidé mi contraseña
                    </a>
                </p>
            </div>

            {{-- Pie de página --}}
            <p class="absolute bottom-5 text-center text-[11px] tracking-wide text-gray-300">
                Universidad Mayor de San Andrés
            </p>
        </div>
    </div>

    {{-- ── JavaScript inline ── --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Toggle password visibility
            const toggle   = document.getElementById('togglePassword');
            const passInput = document.getElementById('password');
            const eyeOpen   = document.getElementById('eyeOpen');
            const eyeClosed = document.getElementById('eyeClosed');

            if (toggle && passInput) {
                toggle.addEventListener('click', () => {
                    const isPwd = passInput.type === 'password';
                    passInput.type = isPwd ? 'text' : 'password';
                    eyeOpen.classList.toggle('hidden', isPwd);
                    eyeClosed.classList.toggle('hidden', !isPwd);
                });
            }

            // Loading state on submit
            const form = document.getElementById('loginForm');
            const btn  = document.getElementById('btnLogin');
            if (form && btn) {
                form.addEventListener('submit', () => btn.classList.add('is-loading'));
            }
        });
    </script>
</body>
</html>

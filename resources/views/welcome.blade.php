@php
    $setting = \App\Models\AppSetting::current();
    $appName = $setting->company_name ?? config('app.name');
    $logoUrl = $setting->company_logo_url;
    $landingIglesiaCentral = \App\Models\Iglesias::query()->first();
    $landingIglesiaTenant = rescue(fn () => \App\Models\TenantIglesia::currentFromCentral(), null, false);
    $rightLogoUrl = $landingIglesiaTenant?->logo_derecha_url ?: $landingIglesiaCentral?->logo_derecha_url;
    $hasRegisterOrganization = \Illuminate\Support\Facades\Route::has('register.organization');
    $hasRegister = \Illuminate\Support\Facades\Route::has('register');
    $canRegisterOrganization = ! \App\Models\Iglesias::registrationLocked();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $appName }}</title>
    <link rel="icon" type="image/png" href="{{ asset('image/Logo_guest.png') }}?v=holyapp">
    <link rel="shortcut icon" href="{{ asset('image/Logo_guest.png') }}?v=holyapp">
    <link rel="apple-touch-icon" href="{{ asset('image/Logo_guest.png') }}?v=holyapp">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700|playfair-display:600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --bg: #f7f4ec;
            --surface: rgba(255, 255, 255, 0.86);
            --border: rgba(18, 38, 31, 0.1);
            --text: #12261f;
            --muted: #587065;
            --accent: #214b3d;
            --accent-soft: #d9e8e1;
            --shadow: 0 20px 45px rgba(18, 38, 31, 0.08);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-width: 320px;
            font-family: 'Manrope', sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(33, 75, 61, 0.08), transparent 28%),
                linear-gradient(180deg, #fbf8f2 0%, var(--bg) 100%);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .container {
            width: min(980px, calc(100% - 2rem));
            margin: 0 auto;
        }

        .header {
            padding: 1.2rem 0;
        }

        .header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.9rem 1rem;
            border: 1px solid var(--border);
            border-radius: 20px;
            background: var(--surface);
            backdrop-filter: blur(10px);
            box-shadow: var(--shadow);
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 0.9rem;
            min-width: 0;
        }

        .brand-mark {
            width: 50px;
            height: 50px;
            flex-shrink: 0;
            border-radius: 16px;
            border: 1px solid var(--border);
            background: #fff;
            display: grid;
            place-items: center;
            overflow: hidden;
        }

        .brand-mark img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .brand-mark svg {
            width: 26px;
            height: 26px;
            stroke: var(--accent);
        }

        .brand-copy {
            min-width: 0;
        }

        .brand-kicker {
            font-size: 0.74rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .brand-title {
            margin-top: 0.15rem;
            font-size: 1rem;
            font-weight: 800;
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            padding: 0.88rem 1.25rem;
            font-size: 0.94rem;
            font-weight: 700;
            transition: transform 0.2s ease, background 0.2s ease, color 0.2s ease;
        }

        .button:hover {
            transform: translateY(-1px);
        }

        .button-primary {
            color: #fff;
            background: var(--accent);
        }

        .button-secondary {
            color: var(--accent);
            background: #fff;
            border: 1px solid var(--border);
        }

        .hero {
            padding: 4.5rem 0 3rem;
        }

        .hero-card {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(180px, 280px);
            gap: 2rem;
            align-items: center;
            padding: 3rem;
            border-radius: 32px;
            border: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.78);
            box-shadow: var(--shadow);
        }

        .hero-copy {
            min-width: 0;
        }

        .eyebrow {
            display: inline-block;
            margin-bottom: 1rem;
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .hero-title {
            margin: 0;
            max-width: 11ch;
            font-family: 'Playfair Display', serif;
            font-size: clamp(3rem, 8vw, 5.4rem);
            line-height: 0.98;
            color: var(--text);
        }

        .hero-description {
            max-width: 60ch;
            margin: 1.4rem 0 0;
            font-size: 1rem;
            line-height: 1.9;
            color: var(--muted);
        }

        .hero-actions {
            display: flex;
            gap: 0.8rem;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        .hero-logo-side {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-logo-frame {
            width: min(100%, 250px);
            aspect-ratio: 1;
            display: grid;
            place-items: center;
            border-radius: 28px;
            border: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.68);
            padding: 1.4rem;
        }

        .hero-logo-frame img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .summary {
            padding-bottom: 4rem;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
        }

        .summary-card {
            padding: 1.35rem;
            border-radius: 22px;
            border: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.72);
        }

        .summary-card h3 {
            margin: 0;
            font-size: 1rem;
            color: var(--text);
        }

        .summary-card p {
            margin: 0.7rem 0 0;
            font-size: 0.92rem;
            line-height: 1.75;
            color: var(--muted);
        }

        .footer {
            padding: 0 0 2.5rem;
        }

        .footer-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding-top: 1.25rem;
            border-top: 1px solid var(--border);
        }

        .footer-copy,
        .footer-note,
        .footer-credits {
            font-size: 0.84rem;
            color: var(--muted);
        }

        .footer-credits strong {
            color: var(--text);
        }

        @media (max-width: 760px) {
            .container {
                width: min(100% - 1.2rem, 100%);
            }

            .header-inner,
            .footer-inner {
                flex-direction: column;
                align-items: stretch;
            }

            .header-actions {
                justify-content: flex-start;
            }

            .hero {
                padding-top: 2.4rem;
            }

            .hero-card {
                grid-template-columns: 1fr;
                padding: 1.6rem;
            }

            .summary-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-inner">
                <a href="{{ url('/') }}" class="brand">
                    <div class="brand-mark">
                        @if ($logoUrl)
                            <img src="{{ $logoUrl }}" alt="Logo de {{ $appName }}">
                        @else
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M12 2v20M5 7h14M7.5 12h9M9.5 17h5" />
                            </svg>
                        @endif
                    </div>
                    <div class="brand-copy">
                        <div class="brand-kicker">Gestión Parroquial</div>
                        <div class="brand-title">Holy App</div>
                    </div>
                </a>

                <div class="header-actions">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="button button-secondary">Ir al panel</a>
                    @else
                        <a href="{{ route('login') }}" class="button button-secondary">Iniciar sesión</a>
                        @if ($hasRegisterOrganization && $canRegisterOrganization)
                            <a href="{{ route('register.organization') }}" class="button button-primary">Configurar parroquia</a>
                        @elseif ($hasRegister && $canRegisterOrganization)
                            <a href="{{ route('register') }}" class="button button-primary">Crear cuenta</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="container">
                <div class="hero-card">
                    <div class="hero-copy">
                        <div class="eyebrow">Archivo parroquial ordenado</div>
                        <h1 class="hero-title">Una forma simple de gestionar la vida sacramental.</h1>
                        <p class="hero-description">
                            Registra feligreses, sacramentos, certificados y procesos parroquiales desde un solo lugar,
                            con una interfaz clara y enfocada en el trabajo diario.
                        </p>

                        <div class="hero-actions">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="button button-primary">Entrar al sistema</a>
                            @else
                                <a href="{{ route('login') }}" class="button button-primary">Abrir el sistema</a>
                                @if ($hasRegisterOrganization && $canRegisterOrganization)
                                    <a href="{{ route('register.organization') }}" class="button button-secondary">Registrar parroquia</a>
                                @endif
                            @endauth
                        </div>
                    </div>

                    @if ($rightLogoUrl)
                        <div class="hero-logo-side">
                            <div class="hero-logo-frame">
                                <img src="{{ $rightLogoUrl }}" alt="Logo derecho">
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <section class="summary">
            <div class="container">
                <div class="summary-grid">
                    <article class="summary-card">
                        <h3>Feligresía</h3>
                        <p>Expedientes personales y familiares listos para consulta y seguimiento.</p>
                    </article>

                    <article class="summary-card">
                        <h3>Sacramentos</h3>
                        <p>Registro ordenado de bautismos, confirmaciones, comuniones y matrimonios.</p>
                    </article>

                    <article class="summary-card">
                        <h3>Certificados</h3>
                        <p>Documentos en PDF con un flujo claro para emisión y resguardo.</p>
                    </article>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-inner">
                <div class="footer-copy">{{ $appName }}</div>
                <div class="footer-note">Gestión parroquial para sacramentos, feligresía y certificados.</div>
                <div class="footer-credits">Créditos: desarrollado por <strong>NekoTech</strong>.</div>
            </div>
        </div>
    </footer>
</body>
</html>

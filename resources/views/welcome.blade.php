<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Holy Manager | Gestion para Iglesias</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&family=playfair-display:600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --ink-950: #121230;
            --ink-900: #1a1d4e;
            --ink-700: #273088;
            --lavender-100: #f1f0fa;
            --lavender-200: #e6e4f5;
            --lavender-300: #d2cdee;
            --white: #ffffff;
            --text-muted: #6b6f82;
            --brand: #4f5cf0;
            --brand-strong: #3a46d1;
            --gold: #9f7c34;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Figtree', sans-serif;
            background: var(--lavender-100);
            color: var(--ink-950);
        }

        .container { max-width: 1180px; margin: 0 auto; padding: 0 18px; }

        .lp-nav-wrap {
            background: #f8f8fc;
            border-bottom: 1px solid #e8e7f3;
            position: sticky;
            top: 0;
            z-index: 40;
        }
        .lp-nav {
            min-height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }
        .logo {
            text-decoration: none;
            color: var(--ink-950);
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-style: italic;
            font-size: 1.8rem;
            line-height: 1;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 24px;
        }
        .nav-links a {
            text-decoration: none;
            color: #4a4f67;
            font-size: 14px;
            border-bottom: 2px solid transparent;
            padding-bottom: 2px;
        }
        .nav-links a:first-child { color: var(--brand-strong); border-bottom-color: var(--brand-strong); }
        .nav-actions { display: flex; align-items: center; gap: 14px; }
        .btn-link {
            color: #2f3353;
            font-size: 14px;
            text-decoration: none;
        }
        .btn-sign {
            text-decoration: none;
            background: var(--brand);
            color: var(--white);
            border-radius: 10px;
            padding: 10px 16px;
            font-size: 14px;
            font-weight: 600;
            transition: .2s ease;
        }
        .btn-sign:hover { background: var(--brand-strong); }

        .hero {
            background: radial-gradient(circle at 0% 0%, #2f37a6 0%, var(--ink-900) 42%, var(--ink-950) 100%);
            color: var(--white);
            padding: 72px 0 68px;
        }
        .hero-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            align-items: center;
            gap: 34px;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid rgba(255,255,255,.22);
            color: #ccd1ff;
            border-radius: 999px;
            padding: 7px 12px;
            font-size: 12px;
            margin-bottom: 18px;
        }
        .hero-badge-dot { width: 8px; height: 8px; border-radius: 50%; background: #8fa0ff; }
        .hero-title {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-size: clamp(2.3rem, 6vw, 4rem);
            line-height: 1;
            margin-bottom: 18px;
        }
        .hero-copy {
            color: #d2d8ff;
            font-size: 1.1rem;
            line-height: 1.55;
            max-width: 520px;
            margin-bottom: 30px;
        }
        .hero-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .btn-primary {
            text-decoration: none;
            border-radius: 12px;
            background: var(--brand);
            color: var(--white);
            padding: 12px 20px;
            font-weight: 600;
            font-size: 14px;
            border: 1px solid rgba(255,255,255,.08);
            transition: .2s ease;
        }
        .btn-primary:hover { background: var(--brand-strong); }
        .btn-secondary {
            text-decoration: none;
            border-radius: 12px;
            color: var(--white);
            padding: 12px 20px;
            font-weight: 600;
            font-size: 14px;
            border: 1px solid rgba(255,255,255,.24);
            background: rgba(255,255,255,.07);
            transition: .2s ease;
        }
        .btn-secondary:hover { background: rgba(255,255,255,.16); }
        .hero-media {
            min-height: 360px;
            border-radius: 18px;
            border: 1px solid rgba(255,255,255,.12);
            overflow: hidden;
            box-shadow: 0 20px 45px rgba(0,0,0,.25);
            background-image: linear-gradient(140deg, rgba(13,15,37,.48), rgba(13,15,37,.22)), url('https://images.unsplash.com/photo-1511632765486-a01980e01a18?auto=format&fit=crop&w=1200&q=80');
            background-position: center;
            background-size: cover;
        }

        .trust {
            background: var(--lavender-200);
            padding: 28px 0;
            border-top: 1px solid #dcdaee;
            border-bottom: 1px solid #dcdaee;
        }
        .trust-label {
            text-align: center;
            font-size: 11px;
            letter-spacing: .18em;
            color: #8f91a6;
            margin-bottom: 20px;
        }
        .trust-list {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            text-align: center;
            color: #42465f;
            font-size: 27px;
        }
        .trust-item span {
            margin-left: 8px;
            font-size: 1.6rem;
            font-family: 'Playfair Display', serif;
        }

        .stats-wrap { padding: 48px 0; }
        .stats-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 14px;
        }
        .stats-main, .stats-side {
            border-radius: 18px;
            padding: 30px;
        }
        .stats-main {
            background: #f4f3fb;
            border: 1px solid #e2dff4;
        }
        .stats-side {
            background: linear-gradient(130deg, #5f67f1, #4c56dd);
            color: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .stats-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.15rem;
            margin-bottom: 18px;
            line-height: 1.1;
        }
        .stats-duo {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .stat-number {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            line-height: 1;
            margin-bottom: 8px;
            color: #3246cc;
        }
        .stat-number.alt { color: var(--gold); }
        .stat-text { color: var(--text-muted); font-size: 14px; }
        .shield {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            margin: 0 auto 12px;
            background: rgba(255,255,255,.16);
            display: grid;
            place-items: center;
            font-size: 22px;
        }
        .uptime { font-size: 2.9rem; font-family: 'Playfair Display', serif; line-height: 1; margin-bottom: 10px; }
        .uptime-copy { font-size: 14px; color: rgba(255,255,255,.88); }

        .features {
            padding: 62px 0;
            background: var(--lavender-200);
            border-top: 1px solid #d9d7ec;
            border-bottom: 1px solid #d9d7ec;
        }
        .section-top {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 30px;
        }
        .eyebrow { color: #878aa1; letter-spacing: .16em; font-size: 11px; margin-bottom: 12px; text-transform: uppercase; }
        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.2rem, 4vw, 3.3rem);
            line-height: 1.04;
            max-width: 720px;
        }
        .features-link { color: var(--brand-strong); text-decoration: none; font-size: 14px; font-weight: 600; }

        .feature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }
        .feature-card {
            background: transparent;
        }
        .feature-media {
            border-radius: 18px;
            min-height: 260px;
            margin-bottom: 18px;
            background-size: cover;
            background-position: center;
            border: 1px solid #cbc6e8;
        }
        .feature-media.one { background-image: url('https://images.unsplash.com/photo-1460036521480-ff49c08c2781?auto=format&fit=crop&w=1000&q=80'); }
        .feature-media.two { background-image: url('https://source.unsplash.com/1200x900/?church,classroom,teaching'); }
        .feature-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            margin-bottom: 10px;
            line-height: 1.1;
        }
        .feature-copy {
            color: #5f6377;
            font-size: 15px;
            line-height: 1.65;
            margin-bottom: 13px;
        }
        .feature-tag {
            display: inline-block;
            color: var(--brand-strong);
            font-size: 11px;
            letter-spacing: .11em;
            text-transform: uppercase;
            font-weight: 700;
            text-decoration: none;
        }

        .cta-wrap { padding: 62px 0 80px; }
        .cta {
            background: linear-gradient(145deg, #646cf4 0%, #4d58df 100%);
            color: var(--white);
            border-radius: 24px;
            padding: 54px 28px;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 14px 32px rgba(43, 53, 156, .26);
        }
        .cta::before,
        .cta::after {
            content: '';
            position: absolute;
            width: 520px;
            height: 520px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,.16);
            pointer-events: none;
        }
        .cta::before { top: -360px; left: -120px; }
        .cta::after { bottom: -390px; right: -80px; }
        .cta-title {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-size: clamp(2.2rem, 5vw, 3.8rem);
            line-height: 1;
            margin-bottom: 14px;
        }
        .cta-copy {
            max-width: 700px;
            margin: 0 auto 28px;
            color: rgba(255,255,255,.9);
            font-size: 16px;
            line-height: 1.6;
        }
        .cta-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            flex-wrap: wrap;
        }
        .btn-light {
            text-decoration: none;
            background: #fff;
            color: #2c368f;
            border-radius: 10px;
            padding: 12px 22px;
            font-weight: 600;
            font-size: 14px;
            border: 1px solid rgba(0,0,0,.06);
        }
        .btn-ghost {
            text-decoration: none;
            background: transparent;
            color: #fff;
            border-radius: 10px;
            padding: 12px 22px;
            font-weight: 600;
            font-size: 14px;
            border: 1px solid rgba(255,255,255,.36);
        }

        .footer {
            background: #ecebf7;
            border-top: 1px solid #dad7ea;
            padding: 26px 0;
        }
        .footer-grid {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }
        .footer-logo {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-size: 1.7rem;
            color: #1b1f44;
            line-height: 1;
            margin-bottom: 8px;
        }
        .footer-copy { color: #6f7487; font-size: 12px; max-width: 330px; }
        .footer-links {
            display: flex;
            gap: 18px;
            flex-wrap: wrap;
        }
        .footer-links a {
            color: #7a8097;
            text-decoration: none;
            font-size: 12px;
        }

        @media (max-width: 980px) {
            .hero-grid,
            .stats-grid,
            .feature-grid,
            .trust-list {
                grid-template-columns: 1fr;
            }
            .trust-list { gap: 12px; }
            .section-top {
                align-items: start;
                flex-direction: column;
            }
        }

        @media (max-width: 760px) {
            .lp-nav {
                min-height: auto;
                padding: 12px 0;
                flex-direction: column;
                align-items: start;
            }
            .nav-links,
            .nav-actions {
                width: 100%;
                justify-content: flex-start;
                flex-wrap: wrap;
            }
            .hero { padding: 52px 0; }
            .hero-media { min-height: 260px; }
            .stats-main, .stats-side { padding: 22px; }
            .stats-duo { grid-template-columns: 1fr; }
            .feature-media { min-height: 220px; }
            .cta { padding: 40px 18px; }
        }
    </style>
</head>
<body>
    <header class="lp-nav-wrap">
        <div class="container lp-nav">
            <a href="/" class="logo">Holy Manager</a>

            <nav class="nav-links">
                <a href="#caracteristicas">Caracteristicas</a>
                <a href="#precios">Precios</a>
                <a href="#nosotros">Nosotros</a>
            </nav>

            <div class="nav-actions">
                <a href="{{ route('login') }}" class="btn-link">Iniciar sesion</a>
                <a href="{{ route('register.organization') }}" class="btn-sign">Crear cuenta</a>
            </div>
        </div>
    </header>

    <section class="hero" id="nosotros">
        <div class="container hero-grid">
            <div>
                <div class="hero-badge">
                    <span class="hero-badge-dot"></span>
                    Holy Manager en desarrollo
                </div>
                <h1 class="hero-title">Holy Manager<br>para tu iglesia</h1>
                <p class="hero-copy">Una solucion integral de gestion disenada para comunidades catolicas, evangelicas y adventistas. Digitaliza tu fe y organiza tu congregacion con excelencia.</p>
                <div class="hero-actions">
                    <a href="{{ route('register.organization') }}" class="btn-primary">Comenzar ahora</a>
                    <a href="{{ route('login') }}" class="btn-secondary">Ya tengo cuenta</a>
                </div>
            </div>
            <div class="hero-media" aria-hidden="true"></div>
        </div>
    </section>

    <section class="trust">
        <div class="container">
            <p class="trust-label">CON LA CONFIANZA DE DIVERSAS CONFESIONES</p>
            <div class="trust-list">
                <div class="trust-item">⛪<span>Catolica</span></div>
                <div class="trust-item">📖<span>Evangelica</span></div>
                <div class="trust-item">✝<span>Adventista</span></div>
                <div class="trust-item">🤝<span>Comunitaria</span></div>
            </div>
        </div>
    </section>

    <section class="stats-wrap">
        <div class="container stats-grid">
            <article class="stats-main">
                <h2 class="stats-title">Impacto real en la administracion espiritual</h2>
                <div class="stats-duo">
                    <div>
                        <p class="stat-number">+2,500</p>
                        <p class="stat-text">Miembros registrados gestionados diariamente</p>
                    </div>
                    <div>
                        <p class="stat-number alt">150+</p>
                        <p class="stat-text">Iglesias activas transformando su gestion</p>
                    </div>
                </div>
            </article>

            <aside class="stats-side">
                <div>
                    <div class="shield">🛡</div>
                    <p class="uptime">99.9%</p>
                    <p class="uptime-copy">Disponibilidad del sistema garantizada para tu parroquia</p>
                </div>
            </aside>
        </div>
    </section>

    <section class="features" id="caracteristicas">
        <div class="container">
            <div class="section-top">
                <div>
                    <p class="eyebrow">Capacidades</p>
                    <h2 class="section-title">Herramientas disenadas para la labor ministerial</h2>
                </div>
                <a href="#" class="features-link">Ver todas las funciones ↗</a>
            </div>

            <div class="feature-grid">
                <article class="feature-card">
                    <div class="feature-media one" aria-hidden="true"></div>
                    <h3 class="feature-title">Registros Sacramentales Digitales</h3>
                    <p class="feature-copy">Gestione actas de bautismo, confirmacion y matrimonio con validez historica y seguridad digital. Acceso instantaneo a la genealogia de fe de su parroquia.</p>
                    <a href="#" class="feature-tag">Modulo de archivos</a>
                </article>

                <article class="feature-card">
                    <div class="feature-media two" aria-hidden="true"></div>
                    <h3 class="feature-title">Gestion de Cursos Comunitarios</h3>
                    <p class="feature-copy">Organice los cursos que ofrece la iglesia, asigne instructores, gestione inscripciones y de seguimiento al progreso de cada grupo formativo.</p>
                    <a href="#" class="feature-tag">Modulo de cursos</a>
                </article>
            </div>
        </div>
    </section>

    <section class="cta-wrap" id="precios">
        <div class="container">
            <div class="cta">
                <h2 class="cta-title">Lleve su administracion al<br>siguiente nivel</h2>
                <p class="cta-copy">Holy Manager esta en desarrollo para iglesias catolicas, evangelicas y adventistas. Unase a las comunidades que ya estan preparando su transformacion digital.</p>
                <div class="cta-actions">
                    <a href="{{ route('register.organization') }}" class="btn-light">Prueba gratuita de 14 dias</a>
                    <a href="{{ route('register.organization') }}" class="btn-ghost">Agendar demo</a>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container footer-grid">
            <div>
                <p class="footer-logo">Holy Manager</p>
                <p class="footer-copy">© 2026 Holy Manager. Plataforma en desarrollo para iglesias catolicas, evangelicas y adventistas.</p>
            </div>

            <div class="footer-links">
                <a href="#">Contacto</a>
                <a href="#">Politica de privacidad</a>
                <a href="#">Terminos del servicio</a>
                <a href="#">Centro de ayuda</a>
            </div>
        </div>
    </footer>
</body>
</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema Parroquial UNAH</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Figtree', sans-serif; background: radial-gradient(circle at top, #1f4f7d 0%, #0f6e46 42%, #0b3527 100%); color: #fff; }

        /* NAV */
        .lp-nav { display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 2.5rem; border-bottom: 1px solid rgba(200,165,71,0.3); }
        .nav-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .nav-logo-icon { width: 38px; height: 38px; background: #c8a547; border-radius: 9px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .nav-logo-text { font-size: 15px; font-weight: 600; color: #fff; }
        .nav-links { display: flex; align-items: center; gap: 1.5rem; }
        .nav-links a { font-size: 14px; color: rgba(255,255,255,0.6); text-decoration: none; transition: color .2s; }
        .nav-links a:hover { color: #fff; }
        .btn-nav-login { background: transparent; border: 1px solid rgba(255,255,255,0.2); color: #fff; border-radius: 8px; padding: 8px 18px; font-size: 13px; font-weight: 500; text-decoration: none; transition: background .2s; }
        .btn-nav-login:hover { background: rgba(255,255,255,0.08); }
        .btn-nav-register { background: #0f6e46; border: 1px solid rgba(200,165,71,0.7); color: #fff; border-radius: 8px; padding: 8px 18px; font-size: 13px; font-weight: 500; text-decoration: none; transition: background .2s; }
        .btn-nav-register:hover { background: #0b5c3b; }

        /* HERO */
        .lp-hero { text-align: center; padding: 6rem 2rem 4rem; max-width: 720px; margin: 0 auto; }
        .hero-badge { display: inline-flex; align-items: center; gap: 7px; background: rgba(42,121,179,0.2); border: 1px solid rgba(200,165,71,0.45); border-radius: 20px; padding: 5px 14px; font-size: 12px; color: #f4e6bc; margin-bottom: 1.75rem; }
        .hero-badge-dot { width: 6px; height: 6px; background: #c8a547; border-radius: 50%; }
        .lp-hero h1 { font-size: clamp(2rem, 5vw, 3rem); font-weight: 600; line-height: 1.15; color: #fff; margin-bottom: 1.25rem; }
        .lp-hero h1 span { color: #f4d780; }
        .lp-hero p { font-size: 1.05rem; color: rgba(255,255,255,0.58); line-height: 1.75; margin-bottom: 2.5rem; }
        .hero-btns { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .btn-primary { background: #0f6e46; color: #fff; border: 1px solid rgba(200,165,71,0.7); border-radius: 10px; padding: 13px 30px; font-size: 14px; font-weight: 600; text-decoration: none; display: inline-block; transition: background .2s; }
        .btn-primary:hover { background: #0b5c3b; color: #fff; }
        .btn-outline { background: transparent; color: #fff; border: 1px solid rgba(255,255,255,0.2); border-radius: 10px; padding: 13px 30px; font-size: 14px; text-decoration: none; display: inline-block; transition: background .2s; }
        .btn-outline:hover { background: rgba(255,255,255,0.06); color: #fff; }

        /* STATS */
        .lp-stats { display: flex; justify-content: center; gap: 4rem; padding: 2.5rem 2rem; border-top: 1px solid rgba(255,255,255,0.07); border-bottom: 1px solid rgba(255,255,255,0.07); flex-wrap: wrap; }
        .stat-item { text-align: center; }
        .stat-num { font-size: 1.9rem; font-weight: 600; color: #fff; }
        .stat-label { font-size: 12px; color: rgba(255,255,255,0.4); margin-top: 4px; }

        /* FEATURES */
        .lp-section { padding: 5rem 2.5rem; max-width: 960px; margin: 0 auto; }
        .section-eyebrow { font-size: 12px; color: #f4d780; text-transform: uppercase; letter-spacing: .1em; margin-bottom: .6rem; }
        .section-title { font-size: 1.9rem; font-weight: 600; color: #fff; margin-bottom: .85rem; }
        .section-sub { font-size: 14px; color: rgba(255,255,255,0.48); line-height: 1.75; margin-bottom: 3rem; }

        .feat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 16px; }
        .feat-card { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.09); border-radius: 14px; padding: 1.6rem; transition: border-color .2s; }
        .feat-card:hover { border-color: rgba(200,165,71,0.6); }
        .feat-icon { width: 40px; height: 40px; border-radius: 9px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; }
        .feat-card h3 { font-size: 14px; font-weight: 600; color: #fff; margin-bottom: 7px; }
        .feat-card p { font-size: 13px; color: rgba(255,255,255,0.48); line-height: 1.65; }

        /* SACRAMENTOS */
        .sacr-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px; margin-top: 2.5rem; }
        .sacr-card { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07); border-radius: 12px; padding: 1.5rem; text-align: center; }
        .sacr-dot { width: 44px; height: 44px; border-radius: 50%; margin: 0 auto 12px; }
        .sacr-card h4 { font-size: 13px; font-weight: 600; color: #fff; margin-bottom: 4px; }
        .sacr-card p { font-size: 11px; color: rgba(255,255,255,0.38); }

        /* HOW IT WORKS */
        .steps { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 2.5rem; }
        .step { text-align: center; padding: 1.5rem 1rem; }
        .step-num { width: 42px; height: 42px; background: #0f6e46; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 15px; font-weight: 600; color: #fff; margin: 0 auto 1rem; }
        .step h3 { font-size: 14px; font-weight: 600; color: #fff; margin-bottom: 6px; }
        .step p { font-size: 13px; color: rgba(255,255,255,0.45); line-height: 1.65; }

        /* CTA */
        .lp-cta { background: rgba(15,110,70,0.2); border: 1px solid rgba(200,165,71,0.45); border-radius: 18px; padding: 4.5rem 2rem; text-align: center; max-width: 680px; margin: 0 auto 5rem; }
        .lp-cta h2 { font-size: 1.9rem; font-weight: 600; color: #fff; margin-bottom: 1rem; }
        .lp-cta p { font-size: 14px; color: rgba(255,255,255,0.52); margin-bottom: 2.25rem; line-height: 1.7; }

        /* FOOTER */
        .lp-footer { padding: 2.5rem; text-align: center; border-top: 1px solid rgba(200,165,71,0.25); }
        .lp-footer p { font-size: 12px; color: rgba(255,255,255,0.28); }
    </style>
</head>
<body>

    {{-- NAVEGACIÓN --}}
    <nav class="lp-nav">
        <a href="/" class="nav-logo">
            <div class="nav-logo-icon">
                <svg width="20" height="20" fill="none" stroke="#1f4f7d" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </div>
            <span class="nav-logo-text">Sistema Parroquial UNAH</span>
        </a>
        <div class="nav-links">
            <a href="#caracteristicas">Características</a>
            <a href="#sacramentos">Sacramentos</a>
            <a href="#como-funciona">¿Cómo funciona?</a>
            <a href="{{ route('login') }}" class="btn-nav-login">Iniciar sesión</a>
            <a href="{{ route('register.organization') }}" class="btn-nav-register">Registrar parroquia</a>
        </div>
    </nav>

    {{-- HERO --}}
    <section class="lp-hero">
        <div class="hero-badge">
            <span class="hero-badge-dot"></span>
            Archivo Sagrado Digital
        </div>
        <h1>Gestión parroquial <span>moderna y ordenada</span></h1>
        <div class="hero-btns">
            <a href="{{ route('register.organization') }}" class="btn-primary">Registrar mi parroquia</a>
            <a href="{{ route('login') }}" class="btn-outline">Iniciar sesión</a>
        </div>
    </section>

    {{-- CARACTERÍSTICAS --}}
    <section class="lp-section" id="caracteristicas">
        <p class="section-eyebrow">Funcionalidades</p>
        <h2 class="section-title">Todo lo que necesita tu parroquia</h2>
        <p class="section-sub">Desde el censo de feligreses hasta la emisión de certificados digitales, cubrimos cada proceso administrativo de tu parroquia.</p>
        <div class="feat-grid">
            <div class="feat-card">
                <div class="feat-icon" style="background:rgba(42,121,179,0.22)">
                    <svg width="20" height="20" fill="none" stroke="#f4e6bc" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                </div>
                <h3>Censo de feligreses</h3>
                <p>Registro completo con datos personales, historial sacramental y seguimiento de actividad.</p>
            </div>
            <div class="feat-card">
                <div class="feat-icon" style="background:rgba(15,110,70,0.2)">
                    <svg width="20" height="20" fill="none" stroke="#d8f1e6" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3>Certificados digitales</h3>
                <p>Emite actas de bautismo, matrimonio y confirmación en formato PDF con sello parroquial.</p>
            </div>
            <div class="feat-card">
                <div class="feat-icon" style="background:rgba(161,59,59,0.2)">
                    <svg width="20" height="20" fill="none" stroke="#f0b4b4" stroke-width="2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                </div>
                <h3>Registro de matrimonios</h3>
                <p>Historial completo con datos de contrayentes, testigos y fecha de celebración.</p>
            </div>
            <div class="feat-card">
                <div class="feat-icon" style="background:rgba(42,121,179,0.2)">
                    <svg width="20" height="20" fill="none" stroke="#cbe4f6" stroke-width="2" viewBox="0 0 24 24"><path d="M12 6.253v11.494m-5.747-8.12l11.494 4.373M6.253 14.373l11.494-4.373"/></svg>
                </div>
                <h3>Inscripción a cursos</h3>
                <p>Gestiona catequesis, confirmación y otros cursos con listas de inscritos en tiempo real.</p>
            </div>
            <div class="feat-card">
                <div class="feat-icon" style="background:rgba(200,165,71,0.2)">
                    <svg width="20" height="20" fill="none" stroke="#f4d780" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
                <h3>Dashboard de actividad</h3>
                <p>Visualiza la actividad sacramental mensual y anual con gráficos claros y métricas clave.</p>
            </div>
            <div class="feat-card">
                <div class="feat-icon" style="background:rgba(200,165,71,0.18)">
                    <svg width="20" height="20" fill="none" stroke="#f4e6bc" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
                </div>
                <h3>Multi-parroquia</h3>
                <p>Cada parroquia tiene su propia base de datos aislada. Seguridad y privacidad garantizadas.</p>
            </div>
        </div>
    </section>

    {{-- SACRAMENTOS --}}
    <section class="lp-section" id="sacramentos" style="padding-top: 0;">
        <p class="section-eyebrow">Sacramentos</p>
        <h2 class="section-title">Custodiando cada momento sagrado</h2>
        <p class="section-sub">Registra, consulta y certifica todos los sacramentos de tu comunidad desde un solo sistema.</p>
        <div class="sacr-grid">
            <div class="sacr-card">
                <div class="sacr-dot" style="background:rgba(42,121,179,0.5)"></div>
                <h4>Bautismo</h4>
                <p>Registro y certificado</p>
            </div>
            <div class="sacr-card">
                <div class="sacr-dot" style="background:rgba(161,59,59,0.5)"></div>
                <h4>Matrimonio</h4>
                <p>Acta y testigos</p>
            </div>
            <div class="sacr-card">
                <div class="sacr-dot" style="background:rgba(15,110,70,0.5)"></div>
                <h4>Confirmación</h4>
                <p>Padrinos y padrinas</p>
            </div>
            <div class="sacr-card">
                <div class="sacr-dot" style="background:rgba(200,165,71,0.5)"></div>
                <h4>Comunión</h4>
                <p>Historial sacramental</p>
            </div>
            <div class="sacr-card">
                <div class="sacr-dot" style="background:rgba(42,121,179,0.5)"></div>
                <h4>Cursos</h4>
                <p>Inscripciones activas</p>
            </div>
        </div>
    </section>

    {{-- CÓMO FUNCIONA --}}
    <section class="lp-section" id="como-funciona" style="padding-top: 0;">
        <p class="section-eyebrow">¿Cómo funciona?</p>
        <h2 class="section-title">En 3 pasos ya estás operando</h2>
        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <h3>Registra tu parroquia</h3>
                <p>Crea tu cuenta con los datos básicos de tu parroquia y comienza a gestionar de inmediato.</p>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <h3>Configura tu equipo</h3>
                <p>Agrega al párroco y colaboradores con sus roles y permisos específicos.</p>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <h3>Comienza a gestionar</h3>
                <p>Registra feligreses, sacramentos y emite certificados desde el primer día.</p>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <div style="padding: 0 2rem;">
        <div class="lp-cta">
            <h2>¿Listo para digitalizar tu parroquia?</h2>
            <p>Crea tu cuenta en minutos. Solo necesitas el nombre de tu parroquia y los datos del administrador para comenzar de forma gratuita.</p>
            <a href="{{ route('register.organization') }}" class="btn-primary" style="font-size: 15px; padding: 14px 38px;">
                Crear cuenta de parroquia
            </a>
        </div>
    </div>

    {{-- FOOTER --}}
    <footer class="lp-footer">
        <p>Sistema Parroquial UNAH</p>
    </footer>

</body>
</html>
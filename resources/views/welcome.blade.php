<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de Gestión para Iglesias | Archivo Sagrado</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Figtree', sans-serif; background: #0f0a2e; color: #fff; }

        /* NAV */
        .lp-nav { 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            padding: 1.25rem 2.5rem; 
            border-bottom: 1px solid rgba(255,255,255,0.08);
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(15, 10, 46, 0.95);
            backdrop-filter: blur(10px);
        }
        .nav-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .nav-logo-icon { width: 38px; height: 38px; background: linear-gradient(135deg, #534AB7 0%, #6B61D4 100%); border-radius: 9px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .nav-logo-text { font-size: 15px; font-weight: 600; color: #fff; }
        .nav-links { display: flex; align-items: center; gap: 1.5rem; }
        .nav-links a { font-size: 14px; color: rgba(255,255,255,0.6); text-decoration: none; transition: color .2s; }
        .nav-links a:hover { color: #fff; }
        .btn-nav-login { background: transparent; border: 1px solid rgba(255,255,255,0.2); color: #fff; border-radius: 8px; padding: 8px 18px; font-size: 13px; font-weight: 500; text-decoration: none; transition: background .2s; }
        .btn-nav-login:hover { background: rgba(255,255,255,0.08); }
        .btn-nav-register { background: #534AB7; border: none; color: #fff; border-radius: 8px; padding: 8px 18px; font-size: 13px; font-weight: 500; text-decoration: none; transition: background .2s; }
        .btn-nav-register:hover { background: #6B61D4; }

        /* HERO */
        .lp-hero { text-align: center; padding: 6rem 2rem 4rem; max-width: 800px; margin: 0 auto; }
        .hero-badge { display: inline-flex; align-items: center; gap: 7px; background: rgba(83,74,183,0.2); border: 1px solid rgba(83,74,183,0.4); border-radius: 20px; padding: 5px 14px; font-size: 12px; color: #AFA9EC; margin-bottom: 1.75rem; }
        .hero-badge-dot { width: 6px; height: 6px; background: #7F77DD; border-radius: 50%; }
        .lp-hero h1 { font-size: clamp(2rem, 5vw, 3.2rem); font-weight: 600; line-height: 1.15; color: #fff; margin-bottom: 1.25rem; }
        .lp-hero h1 span { background: linear-gradient(135deg, #7F77DD 0%, #AFA9EC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .lp-hero p { font-size: 1.05rem; color: rgba(255,255,255,0.58); line-height: 1.75; margin-bottom: 2.5rem; max-width: 650px; margin-left: auto; margin-right: auto; }
        .hero-btns { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .btn-primary { background: #534AB7; color: #fff; border: none; border-radius: 10px; padding: 13px 30px; font-size: 14px; font-weight: 600; text-decoration: none; display: inline-block; transition: all .2s; cursor: pointer; }
        .btn-primary:hover { background: #6B61D4; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(83,74,183,0.3); }
        .btn-outline { background: transparent; color: #fff; border: 1px solid rgba(255,255,255,0.2); border-radius: 10px; padding: 13px 30px; font-size: 14px; text-decoration: none; display: inline-block; transition: all .2s; cursor: pointer; }
        .btn-outline:hover { background: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.3); }

        /* TRUST */
        .lp-trust { padding: 2.5rem 2rem; background: rgba(255,255,255,0.03); border-top: 1px solid rgba(255,255,255,0.07); border-bottom: 1px solid rgba(255,255,255,0.07); }
        .trust-content { max-width: 960px; margin: 0 auto; }
        .trust-label { font-size: 12px; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: .1em; margin-bottom: 1rem; }
        .trust-logos { display: flex; align-items: center; justify-content: center; gap: 3rem; flex-wrap: wrap; }
        .trust-item { display: flex; align-items: center; gap: 8px; font-size: 13px; color: rgba(255,255,255,0.5); }
        .trust-icon { width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; }

        /* STATS */
        .lp-stats { display: flex; justify-content: center; gap: 4rem; padding: 2.5rem 2rem; flex-wrap: wrap; }
        .stat-item { text-align: center; }
        .stat-num { font-size: 2.2rem; font-weight: 600; color: #fff; background: linear-gradient(135deg, #7F77DD 0%, #AFA9EC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .stat-label { font-size: 12px; color: rgba(255,255,255,0.4); margin-top: 6px; }

        /* FEATURES */
        .lp-section { padding: 5rem 2.5rem; max-width: 1000px; margin: 0 auto; }
        .section-eyebrow { font-size: 12px; color: #7F77DD; text-transform: uppercase; letter-spacing: .1em; margin-bottom: .6rem; font-weight: 600; }
        .section-title { font-size: 2rem; font-weight: 600; color: #fff; margin-bottom: 1rem; }
        .section-sub { font-size: 14px; color: rgba(255,255,255,0.48); line-height: 1.75; margin-bottom: 3rem; max-width: 650px; }

        .feat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 20px; }
        .feat-card { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.09); border-radius: 14px; padding: 1.8rem; transition: all .3s; }
        .feat-card:hover { border-color: rgba(83,74,183,0.5); background: rgba(83,74,183,0.08); transform: translateY(-4px); }
        .feat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.2rem; }
        .feat-card h3 { font-size: 15px; font-weight: 600; color: #fff; margin-bottom: 8px; }
        .feat-card p { font-size: 13px; color: rgba(255,255,255,0.48); line-height: 1.65; }

        /* DENOMINACIONES */
        .denom-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 14px; margin-top: 2.5rem; }
        .denom-card { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07); border-radius: 12px; padding: 1.6rem; text-align: center; transition: all .2s; cursor: default; }
        .denom-card:hover { border-color: rgba(83,74,183,0.3); background: rgba(83,74,183,0.05); }
        .denom-icon { width: 48px; height: 48px; border-radius: 50%; margin: 0 auto 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
        .denom-card h4 { font-size: 13px; font-weight: 600; color: #fff; margin-bottom: 4px; }
        .denom-card p { font-size: 11px; color: rgba(255,255,255,0.4); }

        /* PROCESS */
        .steps { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 24px; margin-top: 2.5rem; }
        .step { text-align: center; position: relative; }
        .step::before { content: ''; position: absolute; top: 42px; left: 50%; width: 100%; height: 2px; background: linear-gradient(90deg, transparent, rgba(83,74,183,0.3), transparent); transform: translateX(calc(50% + 10px)); }
        .step:last-child::before { display: none; }
        .step-num { width: 48px; height: 48px; background: linear-gradient(135deg, #534AB7 0%, #6B61D4 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 600; color: #fff; margin: 0 auto 1.5rem; position: relative; z-index: 1; }
        .step h3 { font-size: 15px; font-weight: 600; color: #fff; margin-bottom: 8px; }
        .step p { font-size: 13px; color: rgba(255,255,255,0.45); line-height: 1.65; }

        /* CTA */
        .lp-cta { background: linear-gradient(135deg, rgba(83,74,183,0.16) 0%, rgba(107,97,212,0.1) 100%); border: 1px solid rgba(83,74,183,0.35); border-radius: 18px; padding: 5rem 2rem; text-align: center; max-width: 720px; margin: 0 auto 5rem; }
        .lp-cta h2 { font-size: 2rem; font-weight: 600; color: #fff; margin-bottom: 1rem; }
        .lp-cta p { font-size: 14px; color: rgba(255,255,255,0.54); margin-bottom: 2.5rem; line-height: 1.7; max-width: 550px; margin-left: auto; margin-right: auto; }

        /* FOOTER */
        .lp-footer { padding: 3rem 2rem; text-align: center; border-top: 1px solid rgba(255,255,255,0.07); }
        .lp-footer-content { max-width: 960px; margin: 0 auto; }
        .lp-footer p { font-size: 13px; color: rgba(255,255,255,0.35); line-height: 1.7; }
        .lp-footer-links { display: flex; justify-content: center; gap: 2rem; flex-wrap: wrap; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.07); }
        .lp-footer-links a { font-size: 12px; color: rgba(255,255,255,0.35); text-decoration: none; transition: color .2s; }
        .lp-footer-links a:hover { color: rgba(255,255,255,0.6); }

        /* STICKY QUICK ACCESS */
        
        /* RESPONSIVE */
        @media (max-width: 768px) {
            .lp-nav { padding: 1rem 1.5rem; flex-direction: column; gap: 1rem; }
            .nav-links { flex-wrap: wrap; gap: 0.75rem; }
            .lp-hero { padding: 4rem 1.5rem 2.5rem; }
            .step::before { display: none; }
            .trust-logos { gap: 1.5rem; }
            .lp-quick-access { padding: 0.75rem 1rem; }
            .quick-access-inner { gap: 8px; }
        }
    </style>
</head>
<body>

    {{-- NAVEGACIÓN --}}
    <nav class="lp-nav">
        <a href="/" class="nav-logo">
            <div class="nav-logo-icon">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </div>
            <span class="nav-logo-text">Archivo Sagrado</span>
        </a>
        <div class="nav-links">
            <a href="#caracteristicas">Características</a>
            <a href="#denominaciones">Para todas las iglesias</a>
            <a href="#como-funciona">¿Cómo funciona?</a>
            <a href="{{ route('login') }}" class="btn-nav-login">Iniciar sesión</a>
            <a href="{{ route('register.organization') }}" class="btn-nav-register">Registrar iglesia</a>
        </div>
    </nav>



    {{-- HERO --}}
    <section class="lp-hero">
        <div class="hero-badge">
            <span class="hero-badge-dot"></span>
            Plataforma ecuménica
        </div>
        <h1>Gestión administrativa <span>para tu iglesia</span></h1>
        <p>Una solución moderna para administrar tu comunidad de fe. Censo de miembros, registros sacramentales, certificados digitales y mucho más, todo en un solo lugar seguro y ordenado.</p>
        <div class="hero-btns">
            <a href="{{ route('register.organization') }}" class="btn-primary">Comenzar ahora</a>
            <a href="{{ route('login') }}" class="btn-outline">Ya tengo cuenta</a>
        </div>
    </section>

    {{-- CONFIANZA --}}
    <div class="lp-trust">
        <div class="trust-content">
            <div class="trust-label">Confiado por iglesias de</div>
            <div class="trust-logos">
                <div class="trust-item">
                    <svg class="trust-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <span>Católicas</span>
                </div>
                <div class="trust-item">
                    <svg class="trust-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <span>Evangélicas</span>
                </div>
                <div class="trust-item">
                    <svg class="trust-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <span>Mormones</span>
                </div>
                <div class="trust-item">
                    <svg class="trust-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <span>Adventistas</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ESTADÍSTICAS --}}
    <div class="lp-stats">
        <div class="stat-item">
            <div class="stat-num">+2,500</div>
            <div class="stat-label">Miembros registrados</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">150+</div>
            <div class="stat-label">Iglesias activas</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">10k+</div>
            <div class="stat-label">Registros sacramentales</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">99.9%</div>
            <div class="stat-label">Disponibilidad garantizada</div>
        </div>
    </div>

    {{-- CARACTERÍSTICAS --}}
    <section class="lp-section" id="caracteristicas">
        <p class="section-eyebrow">Funcionalidades</p>
        <h2 class="section-title">Todo lo que tu iglesia necesita</h2>
        <p class="section-sub">Desde la administración de miembros hasta la emisión de certificados, cubrimos cada aspecto del registro eclesiástico.</p>
        <div class="feat-grid">
            <div class="feat-card">
                <div class="feat-icon" style="background:rgba(83,74,183,0.2)">
                    <svg width="22" height="22" fill="none" stroke="#AFA9EC" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                </div>
                <h3>Censo de miembros</h3>
                <p>Registro detallado de feligreses con datos personales, historial sacramental y seguimiento de participación.</p>
            </div>

            <div class="feat-card">
                <div class="feat-icon" style="background:rgba(29,158,117,0.15)">
                    <svg width="22" height="22" fill="none" stroke="#5DCAA5" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3>Certificados digitales</h3>
                <p>Emite certificados de sacramentos en PDF con sello digital y validación oficial de tu iglesia.</p>
            </div>

            <div class="feat-card">
                <div class="feat-icon" style="background:rgba(212,83,126,0.15)">
                    <svg width="22" height="22" fill="none" stroke="#ED93B1" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3zm0 2c-1.657 0-3 1.343-3 3 0 .39.084.765.241 1.1.339.966 1.331 1.773 2.514 2.061.554.113 1.131.113 1.685 0 1.183-.288 2.175-1.095 2.514-2.061.157-.335.241-.71.241-1.1 0-1.657-1.343-3-3-3z"/></svg>
                </div>
                <h3>Registros sacramentales</h3>
                <p>Custodia completa de bautismos, matrimonios, confirmaciones y otros sacramentos según tu tradición.</p>
            </div>

            <div class="feat-card">
                <div class="feat-icon" style="background:rgba(55,138,221,0.15)">
                    <svg width="22" height="22" fill="none" stroke="#85B7EB" stroke-width="2" viewBox="0 0 24 24"><path d="M12 6.253v11.494m-5.747-8.12l11.494 4.373M6.253 14.373l11.494-4.373"/></svg>
                </div>
                <h3>Gestión de actividades</h3>
                <p>Administra escuela dominical, grupos de oración, catequesis y todas tus actividades comunitarias.</p>
            </div>

            <div class="feat-card">
                <div class="feat-icon" style="background:rgba(186,117,23,0.15)">
                    <svg width="22" height="22" fill="none" stroke="#EF9F27" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
                <h3>Reportes y estadísticas</h3>
                <p>Visualiza métricas de crecimiento, participación y actividad sacramental con gráficos intuitivos.</p>
            </div>

            <div class="feat-card">
                <div class="feat-icon" style="background:rgba(83,74,183,0.15)">
                    <svg width="22" height="22" fill="none" stroke="#AFA9EC" stroke-width="2" viewBox="0 0 24 24"><path d="M12 15a3 3 0 100-6 3 3 0 000 6z"/><path d="M19.172 13.172a4 4 0 00-5.656-5.656l-.707.707a2 2 0 112.828 2.828l.707.707a4 4 0 000 5.656m-9.9-9.9a4 4 0 015.656-5.656l.707.707"/></svg>
                </div>
                <h3>Seguridad y privacidad</h3>
                <p>Cada iglesia tiene su propia base de datos encriptada. Tu información está completamente protegida.</p>
            </div>
        </div>
    </section>

    {{-- PARA TODAS LAS IGLESIAS --}}
    <section class="lp-section" id="denominaciones" style="padding-top: 0;">
        <p class="section-eyebrow">Ecuménico</p>
        <h2 class="section-title">Diseñado para todas las tradiciones</h2>
        <p class="section-sub">Ya sea católica, evangélica, pentecostal o de cualquier otra denominación cristiana, Archivo Sagrado se adapta a tu iglesia.</p>
        <div class="denom-grid">
            <div class="denom-card">
                <div class="denom-icon" style="background:rgba(83,74,183,0.2)">✝️</div>
                <h4>Católica</h4>
                <p>Sacramentos de la tradición romana</p>
            </div>
            <div class="denom-card">
                <div class="denom-icon" style="background:rgba(29,158,117,0.15)">🕊️</div>
                <h4>Evangélica</h4>
                <p>Registro de conversiones y bautismos</p>
            </div>
            <div class="denom-card">
                <div class="denom-icon" style="background:rgba(212,83,126,0.15)">💫</div>
                <h4>Pentecostal</h4>
                <p>Seguimiento de miembros activos</p>
            </div>
            <div class="denom-card">
                <div class="denom-icon" style="background:rgba(186,117,23,0.15)">📖</div>
                <h4>Adventista</h4>
                <p>Gestión de actividades de la iglesia</p>
            </div>
            <div class="denom-card">
                <div class="denom-icon" style="background:rgba(55,138,221,0.15)">🌐</div>
                <h4>Ortodoxa</h4>
                <p>Registros según la tradición oriental</p>
            </div>
            <div class="denom-card">
                <div class="denom-icon" style="background:rgba(107,97,212,0.15)">✨</div>
                <h4>Otras iglesias</h4>
                <p>Flexible para cualquier denominación</p>
            </div>
        </div>
    </section>

    {{-- CÓMO FUNCIONA --}}
    <section class="lp-section" id="como-funciona" style="padding-top: 0;">
        <p class="section-eyebrow">Proceso simple</p>
        <h2 class="section-title">Tres pasos para empezar</h2>
        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <h3>Registra tu iglesia</h3>
                <p>Crea una cuenta con los datos básicos de tu comunidad. Selecciona tu denominación para una experiencia personalizada.</p>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <h3>Configura tu equipo</h3>
                <p>Agrega pastores, diáconos y colaboradores con roles y permisos específicos para cada función.</p>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <h3>Comienza a gestionar</h3>
                <p>Registra miembros, sacramentos y emite certificados desde el primer día de forma intuitiva.</p>
            </div>
        </div>
    </section>

    {{-- CTA FINAL --}}
    <div style="padding: 0 2rem;">
        <div class="lp-cta">
            <h2>¿Listo para digitalizar tu iglesia?</h2>
            <p>Crea tu cuenta en minutos y comienza a gestionar tu comunidad de forma segura y ordenada. Disponible para todas las denominaciones cristianas.</p>
            <a href="{{ route('register.organization') }}" class="btn-primary" style="font-size: 15px; padding: 14px 38px;">
                Crear cuenta de iglesia
            </a>
        </div>
    </div>

    {{-- FOOTER --}}
    <footer class="lp-footer">
        <div class="lp-footer-content">
            <p>Archivo Sagrado es una plataforma ecuménica de gestión administrativa para iglesias de todas las denominaciones cristianas. Custodiar la fe, la historia y los registros de nuestra comunidad con reverencia, orden y tecnología moderna.</p>
            <div class="lp-footer-links">
                <a href="#privacidad">Privacidad</a>
                <a href="#terminos">Términos de servicio</a>
                <a href="#contacto">Contacto</a>
                <a href="#soporte">Centro de soporte</a>
            </div>
        </div>
    </footer>

</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if(($siteSettings['ai_protection'] ?? '1') === '1')
        <meta name="robots" content="noai, noimageai">
    @endif
    <title>@yield('title', $siteSettings['site_title'] . ' - Galería de Arte')</title>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        :root {
            --color-primary: {{ $siteSettings['color_primary'] }};
            --color-secondary: {{ $siteSettings['color_secondary'] }};
            --color-bg: {{ $siteSettings['color_bg'] }};
        }
        .ai-shield { user-select: none; -webkit-user-drag: none; }
        .badge-new { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }
    </style>
</head>
<body @if(($siteSettings['ai_protection'] ?? '1') === '1') oncontextmenu="if(event.target.tagName==='IMG'||event.target.tagName==='VIDEO') return false;" @endif>
    <header class="navbar">
        <div class="container">
            <a href="{{ route('home') }}" class="brand">
                @if(!empty($siteSettings['logo_path']))
                    <img src="{{ asset('storage/' . $siteSettings['logo_path']) }}" alt="Logo" style="height:32px; width:auto; border-radius:4px;">
                @else
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <polyline points="21 15 16 10 5 21"/>
                    </svg>
                @endif
                {{ $siteSettings['site_title'] }}
            </a>
            <nav class="nav-links">
                <a href="{{ route('home') }}"><i class="fa-solid fa-palette"></i> Catálogo</a>
                <a href="{{ route('favoritas') }}"><i class="fa-solid fa-star" style="color:#fbbf24"></i> Favoritas (<span id="fav-count">0</span>)</a>
                <a href="{{ route('contacto') }}"><i class="fa-solid fa-envelope"></i> Contacto</a>

                @auth
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge"></i> Panel</a>
                        <form action="{{ route('admin.edit-mode') }}" method="POST" style="display:inline">
                            @csrf
                            <button type="submit" class="btn {{ session('edit_mode') ? 'btn-secondary' : 'btn-outline' }}" style="color:#fff; border-color:rgba(255,255,255,0.4); padding:0.35rem 0.7rem;">
                                <i class="fa-solid fa-pen-to-square"></i> Modo Edición: {{ session('edit_mode') ? 'ON' : 'OFF' }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('mis-mensajes') }}"><i class="fa-solid fa-inbox"></i> Mis Mensajes</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn-outline" style="color:#fff; border-color:rgba(255,255,255,0.3); padding:0.35rem 0.7rem;">Salir</button>
                    </form>
                @else
                    <a href="{{ route('login') }}"><i class="fa-solid fa-right-to-bracket"></i> Entrar</a>
                    <a href="{{ route('register') }}" class="btn btn-secondary" style="padding:0.35rem 0.75rem;">Registrarse</a>
                @endauth
            </nav>
        </div>
    </header>

    @if(Auth::check() && Auth::user()->isAdmin() && session('edit_mode'))
        <section style="background:#fffbeb; border-bottom:2px solid #f59e0b; padding:1rem 0;">
            <div class="container">
                <details open>
                    <summary style="font-weight:700; cursor:pointer; color:#92400e;">
                        <i class="fa-solid fa-wand-magic-sparkles"></i> Barra de Personalización (Logo, Textos, Footer y Paleta de Colores)
                    </summary>
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" style="margin-top:1rem; display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:0.85rem;">
                        @csrf
                        <div>
                            <label><small><strong>Título Web</strong></small></label>
                            <input type="text" name="site_title" class="form-control" value="{{ $siteSettings['site_title'] }}" required>
                        </div>
                        <div>
                            <label><small><strong>Logo (imagen)</strong></small></label>
                            <input type="file" name="logo" class="form-control" accept="image/*">
                        </div>
                        <div>
                            <label><small><strong>Título Portada</strong></small></label>
                            <input type="text" name="hero_title" class="form-control" value="{{ $siteSettings['hero_title'] }}" required>
                        </div>
                        <div>
                            <label><small><strong>Subtítulo Descriptivo</strong></small></label>
                            <input type="text" name="hero_subtitle" class="form-control" value="{{ $siteSettings['hero_subtitle'] }}" required>
                        </div>
                        <div>
                            <label><small><strong>Texto del Footer</strong></small></label>
                            <input type="text" name="footer_text" class="form-control" value="{{ $siteSettings['footer_text'] }}" required>
                        </div>
                        <div style="display:flex; gap:0.5rem; align-items:end;">
                            <div>
                                <label><small><strong>Principal</strong></small></label>
                                <input type="color" name="color_primary" value="{{ $siteSettings['color_primary'] }}" style="width:48px; height:38px; border:none; cursor:pointer;">
                            </div>
                            <div>
                                <label><small><strong>Acento</strong></small></label>
                                <input type="color" name="color_secondary" value="{{ $siteSettings['color_secondary'] }}" style="width:48px; height:38px; border:none; cursor:pointer;">
                            </div>
                            <div>
                                <label><small><strong>Fondo</strong></small></label>
                                <input type="color" name="color_bg" value="{{ $siteSettings['color_bg'] }}" style="width:48px; height:38px; border:none; cursor:pointer;">
                            </div>
                        </div>
                        <div style="display:flex; align-items:end; justify-content:space-between; gap:0.5rem;">
                            <label style="font-size:0.85rem;">
                                <input type="checkbox" name="ai_protection" {{ ($siteSettings['ai_protection'] ?? '1') === '1' ? 'checked' : '' }}> Escudo Anti-IA
                            </label>
                            <button type="submit" class="btn btn-primary">Guardar Diseño</button>
                        </div>
                    </form>
                </details>
            </div>
        </section>
    @endif

    <main class="container">
        @if(session('success'))
            <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}</div>
        @endif

        @yield('content')
    </main>

    <div id="cookie-banner" class="cookie-banner hidden">
        <span>Usamos almacenamiento local y cookies para mejorar tu experiencia (RGPD / LOPDGDD).</span>
        <div style="display:flex; gap:0.5rem; flex-shrink:0;">
            <button id="btn-reject-cookies" type="button" class="btn btn-outline" style="color:#fff; border-color:rgba(255,255,255,0.4);">Rechazar</button>
            <button id="btn-accept-cookies" type="button" class="btn btn-secondary">Aceptar</button>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p>{{ $siteSettings['footer_text'] }}</p>
        </div>
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
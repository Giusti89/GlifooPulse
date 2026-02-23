<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Glifoo - {{ $titulo }}</title>
    <meta name="description"
        content="Glifoo es una agencia de publicidad digital que ofrece servicios de marketing digital, diseño web, redes sociales, publicidad en Google y Facebook, entre otros.">
    <meta name="keywords"
        content="Glifoo, agencia de publicidad digital, marketing digital, diseño web, redes sociales, publicidad en Google, publicidad en Facebook">
    <meta name="author" content="Glifoo">
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('./img/logos/Boton.ico') }}">
    <link rel="stylesheet" href="{{ asset('estilo/base.css') }}">
    <link rel="stylesheet" href="{{ $url ?? '' }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <header class="main-header">
        <nav class="navbar">
            <div class="nav-container"> <!-- Logo -->
                <div class="navbar-brand">
                    <a href="{{ route('inicio') }}">Glifoo Pulse</a>
                </div>
                <ul class="nav-links">
                    <a class="link-item" href="{{ route('socios') }}">Clientes</a>
                    <a class="link-item" href="{{ route('planes') }}">Servicios</a>
                </ul> <!-- Botones -->
                <div class="nav-actions">
                    <a href="{{ route('login') }}" class="login">Iniciar sesión</a>
                </div> <!-- Botón hamburguesa -->
                <div class="menu-toggle" id="menu-toggle">☰</div>
            </div> <!-- Menú móvil -->
            <div class="mobile-menu" id="mobile-menu">
                <a href="{{ route('socios') }}">Clientes</a>
                <a href="{{ route('planes') }}">Servicios</a>
                <div class="nav-actionsmobile">
                    <a href="{{ route('login') }}" class="login">Iniciar sesión</a>
                </div>
            </div>
        </nav>
    </header>
    @include('layouts.alertas')
    <main class="main-content">
        {{ $slot }}
    </main>

    {{-- <div class="aviso-cookies" id="aviso-cookies">
        <img class="galleta" src="./img/logos/Boton.webp" alt="Galleta">
        <h3 class="titulo">Cookies</h3>
        <p class="parrafo">Utilizamos cookies propias y de terceros para mejorar nuestros servicios.</p>
        <button class="boton" id="btnCokies">De acuerdo</button>
    </div>
    <div class="fondo-aviso-cookies" id="fondo-aviso-cookies"></div> --}}
    @yield('js')

    <footer>
        <div class="footer-container">
            <div class="footer-grid">
                <div class="footer-col footer-col-main">
                    <h2>Glifoo Pulse</h2>
                    <p>La plataforma que impulsa tu negocio hacia el futuro.</p>
                </div>

                <div class="footer-col">
                    <h3>Producto</h3>
                    <ul>
                        <li><a href="#features">Características</a></li>
                        <li><a href="{{ route('planes') }}">Precios</a></li>

                    </ul>
                </div>

                <div class="footer-col">
                    <h3>Servicios</h3>
                    <ul>
                        <li><a href="https://glifoo.com/">Sobre nosotros</a></li>
                        <li><a href="https://glifoo.com/contacto">Contacto</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h3>Empresa</h3>
                    <ul>
                        <li><a href="https://glifoo.com/">Glifoo</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h3>Legal</h3>
                    <ul>
                        <li><a href="{{ route('terminos') }}">Términos</a></li>
                    </ul>
                </div>
            </div>
            <hr class="footer-divider">
            <div class="footer-bottom">
                <div class="footer-copyright">
                    <p>© 2026 Glifoo Pulse. Todos los derechos reservados.</p>
                </div>

                <div class="footer-social">
                    <a href="https://www.facebook.com/glifoo" target="_blank" rel="noopener">
                        <img src="{{ asset('./img/logos/faceb.png') }}" alt="facebook">
                    </a>
                    {{-- <a href="https://wa.me/+59172501311" target="_blank"
                        rel="noopener">
                        <img src="{{ asset('./img/logos/wpb.png') }}" alt="whatsapp">
                    </a> --}}
                </div>
            </div>
        </div>

    </footer>

    {{-- <script src="./js/avisoCokies.js"></script> --}}
    <script src="{{ asset('./dinamico/index.js') }}"></script>
    <script src="{{ $js ?? '' }}"></script>
</body>

</html>

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
    <link rel="stylesheet" href="{{ asset('./css/index.css') }}">
    <link rel="icon" href="{{ asset('./img/logos/Boton.ico') }}">
    <link rel="stylesheet" href="{{ asset('estilo/base.css') }}">
    <link rel="stylesheet" href="{{ $url ?? '' }}">

</head>

<body>

    <header class="main-header">
        <a class="main-logo" href="{{ route('inicio') }}">
            <img src="{{ asset('./img/logos/GlifooComunicacion.png') }}" alt="">
        </a>
        <nav id="nav" class="main-nav">
            <div class="nav-links">
                @auth
                    <a class="link-item" href="{{ route('admin') }}">Administrar</a>
                @endauth
                
                <a class="link-item" href="{{ route('inicio') }}">Glifoo Pulse</a>
                <a class="link-item" href="{{route('socios')}}">Socios</a>               
                <a class="link-item" href="{{ route('planes') }}">Productos</a>                
                <a class="link-item" href="{{ route('usuariologin') }}">Login</a>
                

            </div>
        </nav>
        <button id="button-menu" class="button-menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </header>
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
    <footer>
        <div class="pie">
            <div class="caja">
                <a href="{{ route('inicio') }}"><img src="{{asset('./img/logos/LogoGlifoo.png')}}" alt=""></a>
            </div>
            <div class="caja">
                <a href="https://api.whatsapp.com/message/CYAKMDYVY2D3F1?autoload=1&app_absent=0" target="_blank"
                    rel="noopener">
                    <img src="{{asset('./img/logos/Whatsapp.png')}}" alt="">
                </a>
                <a href="https://www.tiktok.com/@glifoo?lang=es" target="_blank" rel="noopener"><img
                        src="./img/logos/TikTok.png" alt=""></a>
                <a href="https://twitter.com/Glifoo_cc" target="_blank" rel="noopener">
                    <img src="{{asset('./img/logos/Twitter.png')}}" alt="">
                </a>
                <a href="https://www.youtube.com/channel/UCRETsH6tXdRtO5z0yo0fYAw" target="_blank" rel="noopener">
                    <img src="{{asset('./img/logos/Youtube.png')}}" alt="">
                </a>
                <a href="https://www.instagram.com/glifoo.cc/" target="_blank" rel="noopener">
                    <img src="{{ asset('./img/logos/Instagram.png') }}" alt="">
                </a>
                <a href="https://www.facebook.com/glifoo" target="_blank" rel="noopener">
                    <img src="{{ asset('./img/logos/Facebook.png') }}" alt="">
                </a>
            </div>
        </div>
    </footer>
    <div class="final">
        <div class="copy">
            <a href="{{ route('inicio') }}">
                <p>©2025 Glifoo - Comunicación Digital</p>
            </a>
            {{-- <div>
                <a href="{{ route('privacidad') }}">Políticas de privacidad</a>
            </div> --}}
        </div>
    </div>

    {{-- <script src="./js/avisoCokies.js"></script> --}}
    <script src="{{asset('./dinamico/index.js')}}"></script>
    <script src="{{ $js ?? '' }}"></script>
</body>

</html>

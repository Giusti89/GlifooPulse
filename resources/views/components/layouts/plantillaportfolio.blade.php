@props([
    'titulo' => 'Mi Web',
    'descripcion' => 'Crea tu árbol de enlaces, comparte tus redes sociales y muestra tu portafolio profesional con Glifoo.',
    'keywords' => 'portfolio, proyectos, trabajo, linktree, enlaces, redes sociales, glifoo',
    'icono' => null,
    'backgroud' => 'white',
    'styles' => '',
    'scripts' => '',
    'navItems' => [],
    'robots' => 'index, follow', 
    'locale' => 'es_ES',          
    'imagenOg' => null,           
    'ogUrl' => null,              
    'ogType' => 'profile',        
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', substr($locale, 0, 2)) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- 🟢 Quitamos Str::limit para que el controlador maneje el límite según el plan --}}
    <title>{{ $titulo }} | Glifoo</title>
    <meta name="description" content="{{ $descripcion }}">
    <meta name="keywords" content="{{ $keywords }}">
    <meta name="author" content="Glifoo">
    <meta name="robots" content="{{ $robots }}">

    <!-- Open Graph (Metas para WhatsApp, LinkedIn y Facebook) -->
    <meta property="og:title" content="{{ $titulo }}">
    <meta property="og:description" content="{{ $descripcion }}">
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:url" content="{{ $ogUrl ?? request()->url() }}">
    <meta property="og:locale" content="{{ $locale }}">
    @if($imagenOg)
        <meta property="og:image" content="{{ $imagenOg }}">
    @endif

    <!-- Twitter Cards (Vital para diseñadores y profesionales en X) -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $titulo }}">
    <meta name="twitter:description" content="{{ $descripcion }}">
    @if($imagenOg)
        <meta name="twitter:image" content="{{ $imagenOg }}">
    @endif

    <link rel="icon" href="{{ $icono ? asset($icono) : asset('img/logos/Boton.ico') }}" type="image/x-icon">
    {!! $styles !!}
</head>

<body style="background-color: {{ $backgroud ?? 'white' }}">
    @include('layouts.alertas')
    
    <main class="main-content">
        {{ $slot }}
    </main>

    <div class="piefooter">
        <footer>
            <a href="{{ route('inicio') }}">
                <p>&copy; {{ date('Y') }} Glifoo - Todos los derechos reservados</p>
            </a>
        </footer>
    </div>

    {!! $scripts !!}
</body>
</html>
@props([
    'titulo' => 'Mi Web',
     'descripcion' => 'Glifoo es una agencia de publicidad digital que ofrece servicios de marketing digital, diseño web, redes sociales, publicidad en Google y Facebook, entre otros.',
    'keywords' => 'Glifoo, agencia de publicidad digital, marketing digital, diseño web, redes sociales, publicidad en Google, publicidad en Facebook',
    'icono' => null,
    'backgroud' => 'white',
    'imagenOg' => null,   {{-- Enterprise --}}
    'robots' => 'index, follow',  {{-- Medio en adelante --}}
    'locale' => 'es_ES',  {{-- Enterprise --}}
    'styles' => '',
    'scripts' => '',
])
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO básico -->
    <title>{{ Str::limit($titulo, 60, '') }} | Glifoo</title>
    <meta name="description" content="{{ Str::limit($descripcion, 160, '') }}">
    <meta name="keywords" content="{{ $keywords }}">
    <meta name="author" content="Glifoo">

    <!-- SEO medio -->
    <meta name="robots" content="{{ $robots }}">

    <!-- SEO enterprise -->
    <meta property="og:title" content="{{ Str::limit($titulo, 60, '') }}">
    <meta property="og:description" content="{{ Str::limit($descripcion, 160, '') }}">
    @if($imagenOg)
        <meta property="og:image" content="{{ asset($imagenOg) }}">
    @endif
    <meta property="og:type" content="website">
    <meta property="og:locale" content="{{ $locale }}">

    <link rel="icon" href="{{ $icono ? asset($icono) : asset('img/logos/Boton.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('estilo/spot.css') }}">
</head>
<body style="background-color:{{ $backgroud ?? 'white' }}">
    <main class="main-content">
        {{ $slot }}
    </main>
    <footer>
        <a href="{{ route('inicio') }}">
            <p>&copy; Glifoo 2025 - Todos los derechos reservados</p>
        </a>
    </footer>
</body>
</html>

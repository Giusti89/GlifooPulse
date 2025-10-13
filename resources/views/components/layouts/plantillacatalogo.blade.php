@props([
    'titulo' => 'Mi Web',
    'descripcion' =>
        'Glifoo es una agencia de publicidad digital que ofrece servicios de marketing digital, diseño web, redes sociales, publicidad en Google y Facebook, entre otros.',
    'keywords' =>
        'Glifoo, agencia de publicidad digital, marketing digital, diseño web, redes sociales, publicidad en Google, publicidad en Facebook',
    'icono' => null,
    'backgroud' => 'white',
    'styles' => '',
    'scripts' => '',
    'navItems' => [],
])

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ Str::limit($titulo, 60, '') }} | Glifoo</title>
    <meta name="description" content="{{ Str::limit($descripcion, 160, '') }}">
    <meta name="keywords" content="{{ $keywords ?: 'catalogo, productos, tienda online' }}">
    <meta name="author" content="Glifoo">
    <meta name="robots" content="index, follow">


    <meta property="og:title" content="{{ Str::limit($titulo, 60, '') }}">
    <meta property="og:description" content="{{ Str::limit($descripcion, 160, '') }}">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="es_ES">
    <link rel="icon" href="{{ $icono ? asset($icono) : asset('img/logos/Boton.ico') }}" type="image/x-icon">
</head>

<body style="background-color: {{ $backgroud ?? 'white' }}">
    @include('layouts.alertas')
    <main class="main-content">
        {{ $slot }}
    </main>
    <div class="piefooter">
        <footer>
            <a href="{{ route('inicio') }}">
                <p>&copy; Glifoo 2025 - Todos los derechos reservados</p>
            </a>
        </footer>

    </div>


    {!! $scripts !!}
</body>

</html>

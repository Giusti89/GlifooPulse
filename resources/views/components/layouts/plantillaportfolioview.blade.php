@props([
    'titulo' => 'Detalle del Proyecto',
    'descripcion' => 'Conoce los detalles, galería y especificaciones técnicas de este proyecto en Glifoo.',
    'keywords' => 'proyecto, portafolio, galeria, detalles, glifoo',
    'icono' => null,
    'backgroud' => 'white',
    'styles' => '',
    'scripts' => '',
    'robots' => 'index, follow',
    'locale' => 'es_ES',
    'imagenOg' => null,
    'ogUrl' => null,
    'ogType' => 'article',
    'portfolio' => null,
    'datosTecnicos' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', substr($locale, 0, 2)) }}">

<head>
    @if ($portfolio)
        @php
            $dataSchema = [
                '@context' => 'https://schema.org',
                '@type' => 'CreativeWork',
                'name' => $portfolio->titulo,
                'description' => $portfolio->descripcion ?? $descripcion,
                'url' => request()->url(),
            ];
            if ($portfolio->portada) {
                $dataSchema['image'] = asset('storage/' . $portfolio->portada);
            } elseif ($imagenOg) {
                $dataSchema['image'] = $imagenOg;
            }
            if ($datosTecnicos && !empty($datosTecnicos->cliente)) {
                $dataSchema['provider'] = [
                    '@type' => 'Organization',
                    'name' => $datosTecnicos->cliente,
                ];
            }
            if ($portfolio->created_at) {
                $dataSchema['dateCreated'] = $portfolio->created_at->toIso8601String();
            } else {
                $dataSchema['dateCreated'] = date('c');
            }
        @endphp
        <script type="application/ld+json">
        {!! json_encode($dataSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
        </script>
    @endif

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $titulo }}</title>
    <meta name="description" content="{{ $descripcion }}">
    <meta name="keywords" content="{{ $keywords }}">
    <meta name="author" content="Glifoo">
    <meta name="robots" content="{{ $robots }}">
    <meta property="og:title" content="{{ $titulo }}">
    <meta property="og:description" content="{{ $descripcion }}">
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:url" content="{{ $ogUrl ?? request()->url() }}">
    <meta property="og:locale" content="{{ $locale }}">
    <meta property="og:image"
        content="{{ $portfolio && $portfolio->portada ? asset('storage/' . $portfolio->portada) : $imagenOg ?? asset('img/logos/Boton.ico') }}">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $titulo }}">
    <meta name="twitter:description" content="{{ $descripcion }}">
    <meta name="twitter:image"
        content="{{ $portfolio && $portfolio->portada ? asset('storage/' . $portfolio->portada) : $imagenOg ?? asset('img/logos/Boton.ico') }}">

    <link rel="icon" href="{{ $icono ? asset($icono) : asset('img/logos/Boton.ico') }}" type="image/x-icon">
    <link rel="canonical" href="{{ request()->url() }}">
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

@props([
    'titulo' => 'Mi Web',
    'descripcion' =>
        'Glifoo es una plataforma publicitaria digital que te permite crear catálogos, portafolios y tarjetas de presentación virtuales.',
    'keywords' => 'catalogo, productos, tienda online, portafolio digital, glifoo',
    'icono' => null,
    'backgroud' => 'white',
    'styles' => '',
    'scripts' => '',
    'navItems' => [],
    'robots' => 'index, follow',
    'locale' => 'es_ES',
    'imagenOg' => null,
    'ogUrl' => null,
    'ogType' => 'website',
    'descripcionSEO' => '',
    'contenido' => null,
    'categoriapro' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', substr($locale, 0, 2)) }}">

<head>
    @if ($contenido)
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "LocalBusiness",
            "name": "{{ addslashes($titulo) }}",
            "description": "{{ addslashes($descripcionSEO ?? $descripcion) }}",
            "url": "{{ request()->url() }}",
            @if($imagenOg)
            "image": "{{ $imagenOg }}",
            @endif
            @if($contenido->phone)
            "telephone": "{{ $contenido->phone }}",
            @endif
            "address": {
                "@type": "PostalAddress",
                "streetAddress": "{{ addslashes($contenido->pie ?? 'Dirección disponible en el sitio web') }}",
                "addressLocality": "La Paz",
                "addressCountry": "BO"
            }
            @if($categoriapro && $categoriapro->count() > 0),
                "hasOfferCatalog": {
                    "@type": "OfferCatalog",
                    "name": "Catálogo de Productos y Servicios",
                    "itemListElement": [
                    @foreach($categoriapro as $categoria)
                        {
                        "@type": "OfferCatalog",
                        "name": "{{ addslashes($categoria->nombre) }}"
                        {{-- Evaluamos si tiene productos antes de abrir el arreglo hijo --}}
                        @if(isset($categoria->productos) && $categoria->productos->count() > 0),
                        "itemListElement": [
                            @foreach($categoria->productos as $producto)
                            {
                                "@type": "Offer",
                                "price": "{{ $producto->precio ?? '0' }}",
                                "priceCurrency": "BOB",
                                "itemOffered": {
                                "@type": "Product",
                                "name": "{{ addslashes($producto->nombre) }}",
                                "description": "{{ addslashes($producto->descripcion ?? 'Producto disponible en catálogo') }}"
                                }
                            }{{ !$loop->last ? ',' : '' }}
                            @endforeach
                        ]
                        @endif
                        }{{ !$loop->last ? ',' : '' }}
                    @endforeach
                    ]
                }
                @endif
        }
</script>
    @endif
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- 🟢 Quitamos el Str::limit de aquí; los límites ya los maneja tu controlador según el plan --}}
    <title>{{ $titulo }}</title>
    <meta name="description" content="{{ $descripcion }}">
    <meta name="keywords" content="{{ $keywords }}">
    <meta name="author" content="Glifoo">
    <meta name="robots" content="{{ $robots }}">

    <!-- Open Graph (Metas para Redes Sociales y WhatsApp) -->
    <meta property="og:title" content="{{ $titulo }}">
    <meta property="og:description" content="{{ $descripcion }}">
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:url" content="{{ $ogUrl ?? request()->url() }}">
    <meta property="og:locale" content="{{ $locale }}">
    @if ($imagenOg)
        <meta property="og:image" content="{{ $imagenOg }}">
    @endif

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $titulo }}">
    <meta name="twitter:description" content="{{ $descripcion }}">
    @if ($imagenOg)
        <meta name="twitter:image" content="{{ $imagenOg }}">
    @endif

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
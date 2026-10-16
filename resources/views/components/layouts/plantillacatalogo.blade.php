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
    'horarios' => null,
    'redes' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', substr($locale, 0, 2)) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }}</title>

    <meta name="description" content="{{ !empty($descripcionSEO) ? $descripcionSEO : $descripcion }}">
    <meta name="author" content="Glifoo">
    <meta name="robots" content="{{ $robots }}">
    <link rel="canonical" href="{{ $ogUrl ?? request()->url() }}">
    <link rel="icon" href="{{ $icono ? asset($icono) : asset('img/logos/Boton.ico') }}" type="image/x-icon">

    <meta property="og:title" content="{{ $titulo }}">
    <meta property="og:description" content="{{ $descripcion }}">
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:url" content="{{ $ogUrl ?? request()->url() }}">
    <meta property="og:locale" content="{{ $locale }}">
    @if ($imagenOg)
        <meta property="og:image" content="{{ asset($imagenOg) }}">
    @endif

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $titulo }}">
    <meta name="twitter:description" content="{{ $descripcion }}">
    @if ($imagenOg)
        <meta name="twitter:image" content="{{ asset($imagenOg) }}">
    @endif

    @if ($contenido)
        @php
            // ============ LIMPIAR DESCRIPCIÓN ============
            $descripcionLimpia = strip_tags(
                html_entity_decode(
                    $descripcionSEO ?? ($descripcion ?? ($contenido->texto ?? ($contenido->subtitulo_hero ?? ''))),
                    ENT_QUOTES,
                    'UTF-8',
                ),
            );
            if (empty($descripcionLimpia)) {
                $descripcionLimpia = strip_tags($titulo) . ' — Catálogo digital con productos y servicios.';
            }
            $descripcionLimpia = preg_replace('/\s+/', ' ', trim($descripcionLimpia));
            $descripcionLimpia = Str::limit($descripcionLimpia, 300, '');

            // ============ URL E IDs ============
            $urlCatalogo = request()->url();
            $idBusiness = $urlCatalogo . '#business';

            // ============ LOCAL BUSINESS ============
            $localBusiness = [
                '@type' => 'LocalBusiness',
                '@id' => $idBusiness,
                'name' => strip_tags($titulo),
                'description' => $descripcionLimpia,
                'url' => $urlCatalogo,
                'priceRange' => '$$',
            ];

            // Imagen
            if ($imagenOg) {
                $localBusiness['image'] = asset($imagenOg);
            }

            // Logo
            if (!empty($contenido->logo_url)) {
                $localBusiness['logo'] = asset('storage/' . $contenido->logo_url);
            }

            // Teléfono
            if (!empty($contenido->phone)) {
                $localBusiness['telephone'] = strip_tags($contenido->phone);
            }

            // Dirección
            $localBusiness['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => strip_tags($contenido->pie ?? 'Dirección disponible en el sitio web'),
                'addressLocality' => 'La Paz',
                'addressRegion' => 'La Paz',
                'addressCountry' => 'BO',
            ];

            // Coordenadas geográficas (CRÍTICO para IA)
            if (!empty($contenido->latitude) && !empty($contenido->longitude)) {
                $localBusiness['geo'] = [
                    '@type' => 'GeoCoordinates',
                    'latitude' => (float) $contenido->latitude,
                    'longitude' => (float) $contenido->longitude,
                ];
            }

            // Redes sociales (sameAs) — desde tu modelo Social
            $sameAs = [];
            if (!empty($redes) && $redes->isNotEmpty()) {
                foreach ($redes as $red) {
                    if (!empty($red->url)) {
                        $sameAs[] = $red->url;
                    }
                }
            }
            if (!empty($sameAs)) {
                $localBusiness['sameAs'] = $sameAs;
            }

            // ============ HORARIOS ============
            // ⚠️ Ajusta el mapeo si tu "dia" no empieza en lunes = 1
            $diasMapa = [
                1 => 'Monday',
                2 => 'Tuesday',
                3 => 'Wednesday',
                4 => 'Thursday',
                5 => 'Friday',
                6 => 'Saturday',
                7 => 'Sunday',
            ];

            $horariosSchema = [];
            if (!empty($horarios) && $horarios->isNotEmpty()) {
                foreach ($horarios as $h) {
                    if ($h->esta_cerrado || empty($h->apertura) || empty($h->cierre)) {
                        continue;
                    }

                    // Turno 1
                    $horariosSchema[] = [
                        '@type' => 'OpeningHoursSpecification',
                        'dayOfWeek' => $diasMapa[$h->dia] ?? 'Monday',
                        'opens' => \Carbon\Carbon::parse($h->apertura)->format('H:i'),
                        'closes' => \Carbon\Carbon::parse($h->cierre)->format('H:i'),
                    ];

                    // Turno 2 (si existe)
                    if (!empty($h->apertura_2) && !empty($h->cierre_2)) {
                        $horariosSchema[] = [
                            '@type' => 'OpeningHoursSpecification',
                            'dayOfWeek' => $diasMapa[$h->dia] ?? 'Monday',
                            'opens' => \Carbon\Carbon::parse($h->apertura_2)->format('H:i'),
                            'closes' => \Carbon\Carbon::parse($h->cierre_2)->format('H:i'),
                        ];
                    }
                }
            }
            if (!empty($horariosSchema)) {
                $localBusiness['openingHoursSpecification'] = $horariosSchema;
            }

            // ============ SERVICIOS / CATEGORÍAS ============
            $amenities = [];
            if (!empty($categoriapro) && $categoriapro->isNotEmpty()) {
                foreach ($categoriapro as $cat) {
                    $amenities[] = [
                        '@type' => 'LocationFeatureSpecification',
                        'name' => strip_tags($cat->nombre),
                        'value' => true,
                    ];
                }
            }
            if (!empty($amenities)) {
                $localBusiness['amenityFeature'] = $amenities;
            }
        @endphp

        {{-- ============ JSON-LD: LOCAL BUSINESS ============ --}}
        <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@graph' => [$localBusiness],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
    @endif

    <!-- Bloque contenedor de productos -->
    @if ($categoriapro && $categoriapro->count() > 0)
        @php
            $todosLosProductos = collect();
            foreach ($categoriapro as $cat) {
                if (isset($cat->productos) && $cat->productos->count() > 0) {
                    foreach ($cat->productos as $prod) {
                        $todosLosProductos->push($prod);
                    }
                }
            }

            $productosSchema = [];
            foreach ($todosLosProductos as $producto) {
                // Imagen
                $primeraImagen = $producto->imagenes?->first();
                if ($primeraImagen && !empty($primeraImagen->url)) {
                    $urlImagenProducto = asset('storage/' . $primeraImagen->url);
                } elseif (!empty($imagenOg)) {
                    $urlImagenProducto = $imagenOg;
                } else {
                    $urlImagenProducto = asset('img/default-product.jpg');
                }

                // Limpiar descripción
                $descProducto = $producto->descripcion ?? 'Producto disponible en el catálogo de ' . $titulo;
                $descProductoLimpia = strip_tags(html_entity_decode($descProducto, ENT_QUOTES, 'UTF-8'));
                $descProductoLimpia = preg_replace('/\s+/', ' ', trim($descProductoLimpia));
                $descProductoLimpia = Str::limit($descProductoLimpia, 300, '');

                $productosSchema[] = [
                    '@type' => 'Product',
                    '@id' => $urlCatalogo . '#product-' . ($producto->id ?? ''),
                    'name' => strip_tags($producto->nombre),
                    'description' => $descProductoLimpia,
                    'image' => $urlImagenProducto,
                    'sku' => 'GLI-' . ($producto->id ?? ''),
                    'brand' => [
                        '@type' => 'Brand',
                        'name' => strip_tags($titulo),
                    ],
                    'offers' => [
                        '@type' => 'Offer',
                        'price' => (string) ($producto->precio ?? '0'),
                        'priceCurrency' => 'BOB',
                        'availability' => 'https://schema.org/InStock',
                        'url' => $urlCatalogo . '?prod=' . ($producto->slug ?? ''),
                    ],
                ];
            }
        @endphp

        @if (count($productosSchema) > 0)
            <script type="application/ld+json">
            {!! json_encode([
                '@context' => 'https://schema.org',
                '@graph' => $productosSchema,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
            </script>
        @endif
    @endif
    {!! $styles !!}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
    @include('partials.cookie-banner')
</body>

</html>

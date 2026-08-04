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
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "LocalBusiness",
          "name": "{{ addslashes($titulo) }}",
         "description": "{{ addslashes($descripcionSEO ?? $descripcion) }}",
          "url": "{{ request()->url() }}",
          @if($imagenOg)
          "image": "{{ asset($imagenOg) }}",
          @endif
          @if($contenido->phone)
          "telephone": "{{ $contenido->phone }}",
          @endif
          "priceRange": "$$",
          "address": {
            "@type": "PostalAddress",
            "streetAddress": "{{ addslashes($contenido->pie ?? 'Dirección disponible en el sitio web') }}",
            "addressLocality": "La Paz",
            "addressCountry": "BO"
          }
        }
        </script>

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
            @endphp

            @if ($todosLosProductos->count() > 0)
                <script type="application/ld+json">
                [
                  @foreach($todosLosProductos as $producto)
                    @php
                        $primeraImagen = $producto->imagenes?->first(); 
                        if ($primeraImagen && !empty($primeraImagen->url)) {
                            $urlImagenProducto = asset('storage/' . $primeraImagen->url);
                        } elseif (!empty($imagenOg)) {
                            $urlImagenProducto = $imagenOg;
                        } else {
                            $urlImagenProducto = asset('img/default-product.jpg');
                        }

                        // Limpiamos saltos de línea y retornos de carro del texto del cliente para no romper el JSON
                        $descProducto = $producto->descripcion ?? 'Producto disponible en el catálogo de ' . $titulo;
                        $descProductoLimpia = preg_replace('/\s+/', ' ', $descProducto);
                    @endphp
                    {
                      "@context": "https://schema.org",
                      "@type": "Product",
                      "name": "{{ addslashes($producto->nombre) }}",
                      "description": "{{ addslashes($descProductoLimpia) }}",
                      "image": "{{ $urlImagenProducto }}",
                      "sku": "GLI-{{ $producto->id ?? $loop->index }}",
                      "brand": {
                        "@type": "Brand",
                        "name": "{{ addslashes($titulo) }}"
                      },
                      "offers": {
                        "@type": "Offer",
                        "price": "{{ $producto->precio ?? '0' }}",
                        "priceCurrency": "BOB",
                        "availability": "https://schema.org"
                      }
                    }{{ !$loop->last ? ',' : '' }}
                  @endforeach
                ]
                </script>
            @endif
        @endif
    @endif
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

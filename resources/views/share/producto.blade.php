<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- 🔥 META TAGS PARA REDES SOCIALES -->
    <meta property="og:title" content="{{ $meta['titulo'] }}" />
    <meta property="og:description" content="{{ $meta['descripcion'] }}" />
    <meta property="og:image" content="{{ $meta['imagen'] }}" />
    <meta property="og:image:secure_url" content="{{ $meta['imagen'] }}" />
    <meta property="og:image:type" content="image/jpeg" />
    <meta property="og:url" content="{{ $meta['url_compartir'] }}" />
    <meta property="og:type" content="product" />
    <meta property="og:site_name" content="{{ $spot->titulo ?? 'Glifoo' }}" />
    
    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $meta['titulo'] }}">
    <meta name="twitter:description" content="{{ $meta['descripcion'] }}">
    <meta name="twitter:image" content="{{ $meta['imagen'] }}">
    
    <!-- Schema.org para SEO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Product",
        "name": "{{ addslashes($producto->nombre ?? '') }}",
        "description": "{{ addslashes($producto->descripcion ?? '') }}",
        "image": "{{ $meta['imagen'] }}",
        "url": "{{ $meta['url_destino'] }}",
        "brand": {
            "@type": "Brand",
            "name": "{{ addslashes($spot->titulo ?? 'Glifoo') }}"
        },
        "offers": {
            "@type": "Offer",
            "price": "{{ $producto->precio ?? 0 }}",
            "priceCurrency": "BOB",
            "availability": "https://schema.org/InStock"
        }
    }
    </script>

    <title>{{ $meta['titulo'] }}</title>
    
    <!-- Redirección automática -->
    <meta http-equiv="refresh" content="0; url={{ $meta['url_destino'] }}">
    
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f7f7f7;
        }
        .container {
            text-align: center;
            padding: 20px;
            max-width: 500px;
        }
        .product-image {
            max-width: 100%;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h1 {
            font-size: 1.5rem;
            margin: 16px 0 8px;
        }
        p {
            color: #666;
            line-height: 1.6;
        }
        .loading {
            color: #999;
            font-size: 0.9rem;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        @if($meta['imagen'])
            <img src="{{ $meta['imagen'] }}" alt="{{ $producto->nombre ?? 'Producto' }}" class="product-image">
        @endif
        <h1>{{ $producto->nombre ?? 'Producto' }}</h1>
        <p>{{ Str::limit($producto->descripcion ?? '', 200) }}</p>
        @if(isset($producto->precio) && $producto->precio > 0)
            <p><strong>Precio: Bs. {{ number_format($producto->precio, 2) }}</strong></p>
        @endif
        <p class="loading">⬇️ Redirigiendo a {{ $spot->titulo ?? 'Glifoo' }}...</p>
        <p>Si no eres redirigido, <a href="{{ $meta['url_destino'] }}">haz clic aquí</a>.</p>
    </div>

    <script>
        // Redirección automática después de 3 segundos (por si el meta refresh falla)
        setTimeout(function() {
            window.location.href = "{{ $meta['url_destino'] }}";
        }, 3000);
    </script>
</body>
</html>
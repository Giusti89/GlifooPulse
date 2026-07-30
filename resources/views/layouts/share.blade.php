<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Meta tags básicos -->
    <title>{{ $titulo }}</title>
    <meta name="description" content="{{ $descripcion }}">
    
    <!-- 🔥 Open Graph (Facebook, WhatsApp, LinkedIn) -->
    <meta property="og:title" content="{{ $titulo }}">
    <meta property="og:description" content="{{ $descripcion }}">
    <meta property="og:image" content="{{ $imagen }}">
    <meta property="og:image:secure_url" content="{{ $imagen }}">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:url" content="{{ $url }}">
    <meta property="og:type" content="product">
    <meta property="og:site_name" content="{{ $spot->titulo }}">
    
    <!-- 🔥 Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $titulo }}">
    <meta name="twitter:description" content="{{ $descripcion }}">
    <meta name="twitter:image" content="{{ $imagen }}">
    
    <!-- 🔥 Schema.org para productos -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Product",
        "name": "{{ addslashes($producto->nombre) }}",
        "description": "{{ addslashes($producto->descripcion ?? '') }}",
        "image": "{{ $imagen }}",
        "url": "{{ $url }}",
        "brand": {
            "@type": "Brand",
            "name": "{{ addslashes($spot->titulo) }}"
        },
        "offers": {
            "@type": "Offer",
            "price": "{{ $producto->precio ?? 0 }}",
            "priceCurrency": "BOB",
            "availability": "https://schema.org/InStock"
        }
    }
    </script>
    
    <!-- Redirección automática al producto -->
    <meta http-equiv="refresh" content="0; url={{ $url }}">
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('img/logos/Boton.ico') }}" type="image/x-icon">
    
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: #f7f7f7;
        }
        .container {
            text-align: center;
            padding: 20px;
            max-width: 400px;
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
        }
        .btn-share {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 24px;
            background: #25D366;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        @if($imagen)
            <img src="{{ $imagen }}" alt="{{ $producto->nombre }}" class="product-image">
        @endif
        <h1>{{ $producto->nombre }}</h1>
        <p>{{ Str::limit($producto->descripcion ?? '', 200) }}</p>
        @if($producto->precio > 0)
            <p><strong>Precio: Bs. {{ number_format($producto->precio, 2) }}</strong></p>
        @endif
        <p class="loading">⬇️ Redirigiendo a {{ $spot->titulo }}...</p>
        
        <!-- Botón de compartir -->
        <button class="btn-share" onclick="compartirAhora()">
            📱 Compartir este producto
        </button>
    </div>

    <script>
        // Función para compartir directamente desde esta página
        function compartirAhora() {
            if (navigator.share) {
                navigator.share({
                    title: "{{ addslashes($titulo) }}",
                    text: "{{ addslashes($descripcion) }}",
                    url: "{{ $url }}"
                }).catch(() => {});
            } else {
                navigator.clipboard.writeText("{{ addslashes($titulo) }} - {{ $url }}")
                    .then(() => alert('✅ Enlace copiado al portapapeles!'))
                    .catch(() => alert('❌ Error al copiar'));
            }
        }

        // Si la página no se redirige automáticamente, redirigir después de 3 segundos
        setTimeout(() => {
            window.location.href = "{{ $url }}";
        }, 3000);
    </script>
</body>
</html>
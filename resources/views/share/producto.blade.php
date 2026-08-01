<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $meta['titulo'] }}</title>
    <meta name="description" content="{{ $meta['descripcion'] }}">

    <!-- Metatags Open Graph (Vitales para WhatsApp, Móviles y Redes Sociales) -->
    <meta property="og:title" content="{{ $meta['titulo'] }}">
    <meta property="og:description" content="{{ $meta['descripcion'] }}">
    <meta property="og:image" content="{{ $meta['imagen'] }}">
    <meta property="og:image:secure_url" content="{{ $meta['imagen'] }}">
    <meta property="og:url" content="{{ $meta['url_destino'] }}">
    <meta property="og:type" content="product">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $meta['titulo'] }}">
    <meta name="twitter:description" content="{{ $meta['descripcion'] }}">
    <meta name="twitter:image" content="{{ $meta['imagen'] }}">

    <!-- Redirección instantánea si es un usuario real -->
    <script>
        window.location.href = "{{ $meta['url_destino'] }}";
    </script>
</head>

<body>
    <p>Redirigiéndote al producto, por favor espera...</p>
</body>

</html>
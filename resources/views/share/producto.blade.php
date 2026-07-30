<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $meta['titulo'] }}</title>
    <meta name="description" content="{{ $meta['descripcion'] }}">
    <meta property="og:title" content="{{ $meta['titulo'] }}">
    <meta property="og:description" content="{{ $meta['descripcion'] }}">
    <meta property="og:image" content="{{ $meta['imagen'] }}">
    <meta property="og:image:secure_url" content="{{ $meta['imagen'] }}">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:type" content="product">
    <meta property="og:site_name" content="glifoo.org">

    
    <script>
        window.location.href = "{{ $meta['url_destino'] }}";
    </script>
</head>
<body>
    <p>Cargando detalles del producto...</p>
</body>
</html>
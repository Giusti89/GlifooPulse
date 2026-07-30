<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:title" content="{{ $meta['titulo'] }}" />
    <meta property="og:description" content="{{ $meta['descripcion'] }}" />
    <meta property="og:image" content="{{ $meta['imagen'] }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $meta['titulo'] }}">
    <meta name="twitter:description" content="{{ $meta['descripcion'] }}">
    <meta name="twitter:image" content="{{ $meta['imagen'] }}">

    <title>{{ $meta['titulo'] }}</title>
    <script>
        window.location.href = "{{ $meta['url_destino'] }}";
    </script>
</head>
<body>
    <p>Redirigiendo al producto... Si no eres redirigido, <a href="{{ $meta['url_destino'] }}">haz clic aquí</a>.</p>
</body>
</html>
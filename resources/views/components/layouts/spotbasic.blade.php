<!DOCTYPE html>
<html lang="es">
<head>
    <!-- 🔹 Configuración básica -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- 🔹 SEO Básico -->
    <title>{{$titulo}}</title>
    <meta name="description" content="Escribe aquí una descripción atractiva (150-160 caracteres) con palabras clave importantes.">
    <meta name="keywords" content="palabra clave 1, palabra clave 2, palabra clave 3, tu marca">
    <meta name="author" content="Tu Nombre o Empresa">
    
    
    <!-- 🔹 Favicon -->
    
    <link rel="icon" href="{{ $icono ? asset($icono) : asset('./img/logos/Boton.ico') }}" type="image/x-icon">

    <!-- 🔹 CSS -->
    <link rel="stylesheet" href="{{ asset('estilo/spot.css') }}">

</head>
<body style="background-color:{{ $backgroud ?? 'white'}}">    

    <!-- 🔹 Contenido principal -->
    <main class="main-content">
        {{ $slot }}
    </main>
    

    <!-- 🔹 Footer con enlaces internos y externos -->
    <footer>
        <a href="{{ route('inicio') }}">
            <p>&copy; Glifoo 2025 - Todos los derechos reservados </p>
        </a>
        
    </footer>

</body>
</html>

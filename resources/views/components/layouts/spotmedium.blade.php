<!DOCTYPE html>
<html lang="es">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    
    <title>{{$titulo}}</title>
    <meta name="description" content="Escribe aquí una descripción atractiva (150-160 caracteres) con palabras clave importantes.">
    <meta name="keywords" content="palabra clave 1, palabra clave 2, palabra clave 3, tu marca">
    <meta name="author" content="Tu Nombre o Empresa">
    
    
    
    
    <link rel="icon" href="{{ $icono ? asset($icono) : asset('./img/logos/Boton.ico') }}" type="image/x-icon">

    
    <link rel="stylesheet" href="{{ asset('estilo/spot.css') }}">

</head>
<body>    

    
    <main class="main-content">
        {{ $slot }}
    </main>
    

    
    <footer>
        <p>&copy; Glifoo 2025 - Todos los derechos reservados </p>
    </footer>

</body>
</html>
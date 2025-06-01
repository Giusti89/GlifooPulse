<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Glifoo - {{ $titulo }}</title>
    <link rel="icon" href="{{ asset('./img/logos/Boton.ico') }}">
    <link rel="stylesheet" href="{{ asset('estilo/base.css') }}">
    <link rel="stylesheet" href="{{ $url ?? '' }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    
    @include('layouts.alertas')
    <main class="main-content">

        {{ $slot }}
    </main>

    
    @yield('js')
    
   
</body>

</html>

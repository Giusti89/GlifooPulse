<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Glifoo - {{ $titulo }}</title>
    <meta name="description"
        content="Glifoo es una agencia de publicidad digital que ofrece servicios de marketing digital, diseño web, redes sociales, publicidad en Google y Facebook, entre otros.">
    <meta name="keywords"
        content="Glifoo, agencia de publicidad digital, marketing digital, diseño web, redes sociales, publicidad en Google, publicidad en Facebook">
    <meta name="author" content="Glifoo">
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('./img/logos/Boton.ico') }}">
    <link rel="stylesheet" href="{{ asset('estilo/base.css') }}">
    <link rel="stylesheet" href="{{ $url ?? '' }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

</body>

</html>

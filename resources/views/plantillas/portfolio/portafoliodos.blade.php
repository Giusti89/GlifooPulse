@php
    $bgColor = $contenido->background ?? '#ffffff';
    $textColor = $contenido->ctexto ?? '#333333';
    $colsec = $contenido->colsecond ?? '#333333';

    $bgsecundario = $color->fondocolor ?? $bgColor;
    $textsubtitulo = $color->text ?? $colsec;
    $textdescrip = $color->secondary ?? $textColor;

    $botonfondo = $color->primary_button ?? $colsec;
    $botontexto = $color->button_text ?? $textColor;

    $headerfondo = $color->header ?? $colsec;
    $footerfondo = $color->footer ?? $colsec;

    $whatsNumber = Str::of($contenido->phone ?? '')
        ->replaceMatches('/\D+/', '')
        ->__toString();
    $logoUrl = $contenido->logo_url ? '/storage/' . $contenido->logo_url : null;
    $bannerUrl = $contenido->banner_url ? '/storage/' . $contenido->banner_url : null;
    $totalProyectos = $portfolios->count();
    $proyectosActivos = $portfolios->where('estado', 'activo')->count();
@endphp
<x-layouts.plantillaportfolio :titulo="$tituloSEO ?? $titulo" :descripcion="$descripcionSEO" :keywords="$keywordsSEO" :robots="$robots" :imagenOg="$imagenOg ?? $logoUrl"
    :locale="$locale" :backgroud="$bgColor" :icono="$logoUrl" :ogUrl="$ogUrl" :ogType="$ogType" :contenido="$contenido"
    :portfolios="$portfolios" :color="$color">
<h1>hola mundo como</h1>

</x-layouts.plantillaportfolio>

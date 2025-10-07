@php
   
    $tituloSEO = $catalogos->seo_title ?? $catalogos->titulo ?? 'Catálogo de Productos';
    $descripcionSEO = $catalogos->seo_descripcion ?? $catalogos->descripcion ?? 'Descubre nuestro catálogo exclusivo de productos';
    $keywordsSEO = $catalogos->seo_keyword ?? 'productos, catalogo, tienda online, ' . ($catalogos->titulo ?? '');
    
    
    $tituloSEO = Str::limit($tituloSEO, 60, '');
    $descripcionSEO = Str::limit($descripcionSEO, 160, '');
@endphp

<x-layouts.plantillacatalogo 
    :titulo="$tituloSEO"
    :descripcion="$descripcionSEO" 
    :keywords="$keywordsSEO"
    :backgroud="$catalogos->background ?? 'white'"
    :icono="$catalogos->logo ? '/storage/' . $catalogos->logo : null">
     
   
    <div class="catalogo-content">
        <h1>{{ $catalogos->titulo ?? 'Mi Catálogo' }}</h1>
      
    </div>

</x-layouts.plantillacatalogo>
<x-layouts.spotbasic titulo="{{ $titulo }}">
    <link rel="stylesheet" href="{{ asset('./estilo/basico.css') }}">
    <div class="basicoprincipal">
        <div class="banner">
            <img src="{{ asset('/storage/' . $contenido->banner_url) }}" alt="">
        </div>
        <div class="cuerpo">
            <div class="seccionuno">
                <div class="texto">
                    <p>{{$contenido->texto}}
                    </p>
                </div>
                <div class="redes">
                    <img src="{{ asset('./img/logos/correo.png') }}" alt="">
                    <img src="{{ asset('./img/logos/correo.png') }}" alt="">
                    <img src="{{ asset('./img/logos/correo.png') }}" alt="">
                    <img src="{{ asset('./img/logos/correo.png') }}" alt="">
                </div>
            </div>
            <div class="direccion">
                <p>
                    {{$contenido->pie}} <br>
                    La Orquesta Gobernadoras de Bolivia es una institución dedicada a la promoción y enriquecimiento del
                    patrimonio musical en Bolivia
                </p>

            </div>
           
        </div>
    </div>
</x-layouts.spotbasic>

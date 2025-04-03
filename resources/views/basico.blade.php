<x-layouts.spotbasic titulo="{{ $titulo }}">
    <link rel="stylesheet" href="{{ asset('./estilo/basico.css') }}">
    <div class="basicoprincipal">
        <div class="banner">
            <img src="{{ asset('./img/arbolesotono.jpg') }}" alt="">
        </div>
        <div class="cuerpo">
            <div class="seccionuno">
                <div class="texto">
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Neque quo reprehenderit vel ea fugiat
                        commodi. Eius error optio maxime inventore quas nisi aspernatur quaerat enim dolores? A suscipit
                        ullam quae!
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
                    Av. 6 de Agosto N° 2575, La Paz - Bolivia <br>
                    La Orquesta Gobernadoras de Bolivia es una institución dedicada a la promoción y enriquecimiento del
                    patrimonio musical en Bolivia
                </p>

            </div>
           
        </div>
    </div>
</x-layouts.spotbasic>

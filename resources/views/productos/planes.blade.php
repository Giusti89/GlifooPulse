<x-layouts.principal titulo="Planes" url="{{ asset('./estilo/producto.css') }}">
    <div class="principal">
        @foreach ($productos as $item)
            <div class="tarjeta" style="background-image: url(/storage/{{ $item->image_url }})">
                <div class="tarjeta__header">
                    <h2 class="tarjeta__titulo">{{ $item->nombre }}</h2>                    
                </div>

                <div class="tarjeta__body">
                    <p class="tarjeta__descripcion">{{$item->descripcion}}</p>
                </div>
                <div class="tarjeta__precio">
                    <p class="tarjeta__descripcion">{{$item->precio}} $ </p>
                </div>

                <div class="tarjeta__footer">
                   
                    <a href=" {{ route('registro', $item->id) }}"  target="_blank">
                        <button class="tarjeta__boton">Contacto</button>
                    </a>
                  
                </div>

            </div>
        @endforeach
    </div>

</x-layouts.principal>

<x-layouts.principal titulo="Planes" url="{{ asset('./estilo/producto.css') }}">
    <div class="principal">
        @foreach ($productos as $item)
            @if ($item->estado == true)
                <div class="tarjeta" style="background-image: url(/storage/{{ $item->image_url }})">
                    <div class="tarjeta__header">
                        <h2 class="tarjeta__titulo">{{ $item->nombre }}</h2>
                    </div>
                    <div class="tarjeta__body">
                        <p class="tarjeta__descripcion">{!! str($item->descripcion)->sanitizeHtml() !!}</p>
                    </div>
                    <div class="tarjeta__precio">
                        <p class="tarjeta__descripcion">{{ $item->precio }} Bs. </p>
                    </div>

                    <div class="tarjeta__footer">
                        @php
                            $encryptedId = Crypt::encrypt($item->id);
                        @endphp

                        <x-layouts.btnenviodat class="modificar" rutaEnvio="registro" dato="{{ $encryptedId }}" 
                            nombre="REGISTRATE">
                        </x-layouts.btnenviodat>

                    </div>

                </div>
            @endif
        @endforeach
    </div>


</x-layouts.principal>

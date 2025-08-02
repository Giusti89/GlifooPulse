<div>
    <link rel="stylesheet" href="{{ asset('estilo/tiendaplan.css') }}">

    @foreach ($landingsPago as $item)
        <div class="tarjeta" style="background-image: url(/storage/{{ $item->preview_url }})">
            <div class="tarjeta__header">
                <h2 class="tarjeta__titulo">{{ $item->nombre }}</h2>
            </div>

            <div class="tarjeta__body">
                <p class="tarjeta__descripcion">{{ $item->descripcion }}</p>
            </div>
            <div class="tarjeta__precio">
                <p class="tarjeta__descripcion">{{ $item->precio }} Bs.</p>
            </div>

            <div class="tarjeta__footer">
                @php
                    $yaComprado = $item->compradores->contains(Auth::id());
                    $enPendiente = in_array($item->id, $pendientes);
                @endphp

                @if ($yaComprado)
                    <button class="btn btn-secondary" disabled>Ya comprado</button>
                @else
                    <button wire:click="comprar('{{ $item->id }}')" wire:loading.attr="disabled"
                        @if ($enPendiente) disabled @endif class="btn-buy">
                        <span wire:loading.remove>
                            {{ $enPendiente ? 'Solicitado' : 'Comprar' }}
                        </span>

                        <span wire:loading>Procesando…</span>
                    </button>
                @endif
            </div>
        </div>
    @endforeach
</div>

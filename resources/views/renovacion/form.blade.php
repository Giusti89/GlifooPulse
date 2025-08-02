<x-layouts.principal titulo="Renovacion">

    <div class="container">
        <h2>Renovar Suscripción</h2>
        <form method="POST" action="{{ route('renovacion.form') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Meses a renovar</label>
                <select name="meses" class="form-select">
                    <option value="1">1 mes</option>
                    <option value="3">3 meses</option>
                    <option value="6">6 meses</option>
                    <option value="12">12 meses</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Confirmar</button>
        </form>
    </div>
</x-layouts.principal>

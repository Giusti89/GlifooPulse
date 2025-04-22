<!-- mensajes de confirmacion -->
@if (session('msj') == 'susterminada')
    <script>
        Swal.fire({
            title: "No se pudo realizar la solicitud",
            text: "Su suscripción a terminado pongase en contacto con el porveedor del servicio.",
            icon: "warning"
        });
    </script>
@endif

@if (session('msj') == 'sinsuscripcion')
    <script>
        Swal.fire({
            title: "No se pudo realizar la solicitud",
            text: "Tu suscripcion esta en verificación.",
            icon: "warning"
        });
    </script>
@endif

<!-- mensajes de confirmacion -->

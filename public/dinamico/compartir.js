function compartirProducto(boton) {
    const url = boton.getAttribute('data-url');
    const titulo = boton.getAttribute('data-titulo');
    const descripcion = boton.getAttribute('data-descripcion');

    // Detectar si es un dispositivo móvil (iOS / Android)
    const esMovil = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);

    if (esMovil) {
        // SOLUCIÓN MÓVIL: Enviamos ÚNICAMENTE la URL limpia.
        // Al ir sola, la aplicación nativa de WhatsApp forzará la creación de la hermosa tarjeta con foto.
        const whatsappUrl = `whatsapp://send?text=${encodeURIComponent(url)}`;
        window.open(whatsappUrl, '_blank');
    } else {
        // EN COMPUTADORAS (Escritorio)
        if (navigator.share) {
            // Si el navegador de PC soporta la API nativa, compartimos solo la URL
            navigator.share({ url: url })
                .catch((error) => console.log('Interrupción', error));
        } else {
            // COMPORTAMIENTO DESDE PC HACIENDO CLIC A WHATSAPP:
            // Copiamos la URL al portapapeles y abrimos WhatsApp Web pasándole SOLO la URL limpia.
            navigator.clipboard.writeText(url).then(() => {
                const whatsappWebUrl = `https://whatsapp.com{encodeURIComponent(url)}`;
                window.open(whatsappWebUrl, '_blank');

                // Un pequeño retraso para que el usuario no se distraiga con el alert antes de que abra la pestaña
                setTimeout(() => {
                    alert('¡Enlace del producto copiado! Puedes pegarlo en tu chat si la tarjeta no carga automáticamente.');
                }, 1000);
            }).catch(err => {
                const whatsappWebUrl = `https://whatsapp.com{encodeURIComponent(url)}`;
                window.open(whatsappWebUrl, '_blank');
            });
        }
    }
}
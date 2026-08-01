function compartirProducto(boton) {
    // 1. Extraemos las URLs estratégicas del backend
    const urlSeo = boton.getAttribute('data-url-seo'); // La lee el bot para la foto
    const urlDestino = boton.getAttribute('data-url-destino'); // A donde irá el cliente final
    const titulo = boton.getAttribute('data-titulo');
    const descripcion = boton.getAttribute('data-descripcion');
    
    // 2. Mensaje limpio y estructurado con negritas de WhatsApp (*)
    const textoCompartir = `⭐ *${titulo}*\n${descripcion}\n\n Ver producto completo aquí:\n${urlDestino}`;

    // 3. Flujo para Dispositivos Móviles (API Nativa)
    if (navigator.share) {
        navigator.share({
            title: titulo,
            text: textoCompartir, 
            url: urlSeo // Le pasamos la URL SEO al sistema operativo para que fuerce la miniatura
        })
        .catch((error) => console.log('Interrupción al compartir', error));
    } else {
        // 4. Flujo para Escritorio: Copiar datos completos al portapapeles
        navigator.clipboard.writeText(textoCompartir).then(() => {
            // Reemplaza con tu sistema de alertas o SweetAlert
            alert('¡Información del producto y enlace copiados al portapapeles!');
        }).catch(err => {
            // 5. Fallback extremo: Si el portapapeles es bloqueado, abre WhatsApp Web directamente
            // Corregido: Usamos ://whatsapp.com con parámetros limpios
            const whatsappUrl = `https://://whatsapp.com/send?text=${encodeURIComponent(textoCompartir)}`;
            window.open(whatsappUrl, '_blank');
        });
    }
}
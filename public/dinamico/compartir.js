function compartirProducto(boton) {
    const url = boton.getAttribute('data-url');
    const titulo = boton.getAttribute('data-titulo');
    const descripcion = boton.getAttribute('data-descripcion');
    
    // Texto formateado de manera atractiva para redes sociales
    const textoCompartir = `⭐ *${titulo}*\n${descripcion}\n\nVer más detalles aquí:`;

    // 1. Intentar compartir con la API Nativa (Móviles / Tabletas)
    if (navigator.share) {
        navigator.share({
            title: titulo,
            text: `${titulo} - ${descripcion}`,
            url: url
        })
        .catch((error) => console.log('Interrupción al compartir', error));
    } else {
        // 2. Comportamiento en Escritorio: Copiar al portapapeles y avisar al usuario
        const textoCompleto = `${textoCompartir} ${url}`;
        
        navigator.clipboard.writeText(textoCompleto).then(() => {
            // Reemplaza esto con un Toast de SweetAlert o tu sistema de alertas
            alert('¡Enlace y detalles del producto copiados al portapapeles!');
        }).catch(err => {
            // Alternativa extrema si falla el portapapeles (Abrir WhatsApp Web directamente)
            const whatsappUrl = `https://whatsapp.com{encodeURIComponent(textoCompleto)}`;
            window.open(whatsappUrl, '_blank');
        });
    }
}

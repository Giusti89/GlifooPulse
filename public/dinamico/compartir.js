function compartirProducto(boton) {
    const urlSeo = boton.getAttribute('data-url-seo'); // URL limpia para los bots e intermedia
    const urlDestino = boton.getAttribute('data-url-destino'); // URL final con hash para el usuario
    const titulo = boton.getAttribute('data-titulo');
    const descripcion = boton.getAttribute('data-descripcion');
    
    // 1. FLUJO PARA MÓVILES (API NATIVA)
    if (navigator.share) {
        // En móviles mandamos el texto estético y dejamos que la API nativa procese la URL intermedia
        const textoMovil = `⭐ *${titulo}*\n${descripcion}\n\n👉 Ver producto completo aquí:`;
        
        navigator.share({
            title: titulo,
            text: textoMovil, 
            url: urlSeo // El sistema operativo procesará esta URL y jalará la foto de tu controlador
        })
        .catch((error) => console.log('Interrupción al compartir', error));
        
    } else {
        // 2. FLUJO PARA ESCRITORIO (WHATSAPP WEB O PORTAPAPELES)
        // IMPORTANTE: En escritorio compartimos ÚNICAMENTE la URL intermedia para que WhatsApp Web la escanee
        const textoEscritorio = `⭐ *${titulo}*\n${descripcion}\n\n👉 Ver producto completo aquí:\n${urlSeo}`;

        navigator.clipboard.writeText(textoEscritorio).then(() => {
            alert('¡Detalles y enlace del producto copiados! Abre WhatsApp y pégalo.');
        }).catch(err => {
            // Si el portapapeles falla, abrimos la API con la URL de compartir
            const whatsappUrl = `https://whatsapp.com{encodeURIComponent(textoEscritorio)}`;
            window.open(whatsappUrl, '_blank');
        });
    }
}

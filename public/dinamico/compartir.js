async function compartirProducto(elemento) {
    const url = elemento.getAttribute('data-url') || elemento.dataset.url;
    const titulo = elemento.getAttribute('data-titulo') || elemento.dataset.titulo;
    const descripcion = elemento.getAttribute('data-descripcion') || elemento.dataset.descripcion;
    const imagen = elemento.dataset.imagen;

    console.log('🔍 Datos capturados para compartir:', { url, titulo, descripcion });

    if (!url) {
        console.error('❌ Error: No se proporcionó una URL para compartir.');
        return;
    }

    // 1. Armamos el texto estético tal como lo tenías originalmente
    const textoMensaje = `⭐ *${titulo}*\n${descripcion || ''}`;
    
    // 2. Unimos todo: El texto va primero y la URL limpia SIEMPRE al final para activar el scraper
    const textoCompletoParaWhatsApp = `${textoMensaje}\n\n${url}`;

    // Detectar si el usuario está en un dispositivo móvil
    const esMovil = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);

    if (esMovil) {
        if (navigator.share) {
            try {
                await navigator.share({
                    title: titulo,
                    text: textoMensaje, // La API nativa separa el texto de la URL en la hoja de compartir
                    url: url 
                });
                console.log('✅ Compartido de forma nativa exitosamente');
                return;
            } catch (error) {
                if (error.name === 'AbortError' || error.name === 'NotAllowedError') {
                    console.log('ℹ️ El usuario canceló la acción.');
                    return;
                }
                console.warn('⚠️ Falló Web Share API, usando enlace directo:', error);
            }
        }
        
        // Fallback en móvil si falla navigator.share: Envía el texto con la URL al final
        const whatsappUrl = `https://whatsapp.com{encodeURIComponent(textoCompletoParaWhatsApp)}`;
        window.open(whatsappUrl, '_blank');
    } else {
        // En Escritorio / PC: WhatsApp Web procesa perfectamente el texto combinado
        console.log('💻 Dispositivo de escritorio detectado - Abriendo WhatsApp Web');
        const whatsappWebUrl = `https://whatsapp.com{encodeURIComponent(textoCompletoParaWhatsApp)}`;
        window.open(whatsappWebUrl, '_blank');
    }
}
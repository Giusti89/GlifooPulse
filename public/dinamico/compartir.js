async function compartirProducto(elemento) {
    const url = elemento.getAttribute('data-url') || elemento.dataset.url;
    const titulo = elemento.getAttribute('data-titulo') || elemento.dataset.titulo;

    console.log('🔍 Datos capturados para compartir:', { url, titulo });

    if (!url) {
        console.error('❌ Error: No se proporcionó una URL para compartir.');
        return;
    }

    // Detectar si el usuario está en un dispositivo móvil
    const esMovil = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);

    if (esMovil) {
        // En móviles, usamos la API Nativa. Al pasar SOLO la url, la tarjeta de WhatsApp se genera mucho mejor.
        if (navigator.share) {
            try {
                await navigator.share({
                    title: titulo,
                    url: url // Enviamos solo la URL de tu controlador Laravel para activar las etiquetas OG
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
        
        // Fallback en móvil si falla navigator.share: Abrir WhatsApp App directamente
        const whatsappUrl = `https://whatsapp.com{encodeURIComponent(url)}`;
        window.open(whatsappUrl, '_blank');
    } else {
        // En Escritorio / PC: Abrir WhatsApp Web directamente con la URL limpia
        console.log('💻 Dispositivo de escritorio detectado - Abriendo WhatsApp Web');
        const whatsappWebUrl = `https://whatsapp.com{encodeURIComponent(url)}`;
        window.open(whatsappWebUrl, '_blank');
    }
}

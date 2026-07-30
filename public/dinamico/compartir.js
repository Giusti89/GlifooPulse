async function compartirProducto(elemento) {
    // 1. Capturar la URL generada por Laravel
    const url = elemento.getAttribute('data-url') || elemento.dataset.url;
    const titulo = elemento.getAttribute('data-titulo') || elemento.dataset.titulo;

    if (!url) {
        console.error('❌ Error: No se proporcionó una URL válida para compartir.');
        return;
    }

    console.log('🚀 Procesando compartido para:', { url, titulo });

    // 2. Detectar si el usuario navega desde un dispositivo móvil
    const esMovil = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);

    if (esMovil) {
        // En móviles intentamos usar primero la API nativa del sistema operativo
        if (navigator.share) {
            try {
                await navigator.share({
                    title: titulo,
                    url: url // Enviamos solo la URL para maximizar la compatibilidad de la tarjeta
                });
                console.log('✅ Compartido nativo móvil completado con éxito');
                return;
            } catch (error) {
                // Evitamos alertas si el usuario simplemente cerró la ventana de compartir
                if (error.name === 'AbortError' || error.name === 'NotAllowedError') {
                    console.log('ℹ️ El usuario canceló la acción.');
                    return;
                }
                console.warn('⚠️ La API Nativa falló o fue bloqueada, usando fallback directo:', error);
            }
        }
        
        // Fallback en móviles si falla el Web Share nativo: Abrir la App de WhatsApp directamente
        const whatsappAppUrl = `https://whatsapp.com{encodeURIComponent(url)}`;
        window.open(whatsappAppUrl, '_blank');
    } else {
        // En Computadoras de escritorio (PC/Mac): Forzar redirección directa a WhatsApp Web
        console.log('💻 Entorno de escritorio - Abriendo WhatsApp Web');
        const whatsappWebUrl = `https://whatsapp.com{encodeURIComponent(url)}`;
        window.open(whatsappWebUrl, '_blank');
    }
}

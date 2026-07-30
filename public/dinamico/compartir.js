/**
 * Procesa la acción de compartir un producto de forma nativa o vía Web/App
 */
async function compartirProducto(elemento) {
    // 1. Capturar los datos del elemento HTML de forma segura
    const url = elemento.getAttribute('data-url') || elemento.dataset.url;
    const titulo = elemento.getAttribute('data-titulo') || elemento.dataset.titulo;
    const descripcion = elemento.getAttribute('data-descripcion') || elemento.dataset.descripcion || '';

    if (!url) {
        console.error('Error: No se proporcionó una URL para compartir.');
        return;
    }

    // 2. Dar formato estético al mensaje (Negritas para el título)
    const textoMensaje = `*${titulo}*\n${descripcion}`;
    
    // 3. Unir todo dejando la URL siempre al final para activar el scraper de previsualización
    const textoCompletoParaWhatsApp = `${textoMensaje}\n\n${url}`;

    // 4. Detectar si el usuario está navegando desde un dispositivo móvil
    const esMovil = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);

    // 5. Intentar compartir usando la API nativa del celular (Hoja de compartir nativa)
    if (esMovil && navigator.share) {
        try {
            await navigator.share({
                title: titulo,
                text: textoMensaje, 
                url: url 
            });
            console.log('Compartido de forma nativa exitosamente');
            return; 
        } catch (error) {
            // Si el usuario cancela la acción deliberadamente, salimos sin error
            if (error.name === 'AbortError' || error.name === 'NotAllowedError') {
                console.log('El usuario canceló la acción.');
                return;
            }
            console.warn('Falló Web Share API, recurriendo a enlace directo:', error);
        }
    }

    // 6. FALLBACKS: Si falla la API nativa o si están en computadora de escritorio
    if (esMovil) {
        // Enlace optimizado para abrir la aplicación nativa de WhatsApp en celulares
        const whatsappUrl = `https://whatsapp.com{encodeURIComponent(textoCompletoParaWhatsApp)}`;
        window.open(whatsappUrl, '_blank');
    } else {
        // Enlace optimizado para computadoras de escritorio (WhatsApp Web)
        const whatsappWebUrl = `https://whatsapp.com{encodeURIComponent(textoCompletoParaWhatsApp)}`;
        window.open(whatsappWebUrl, '_blank');
    }
}
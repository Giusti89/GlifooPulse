async function compartirProducto(elemento) {
    // 1. Obtener datos del botón de forma segura
    const url = elemento.getAttribute('data-url') || elemento.dataset.url;
    const titulo = elemento.getAttribute('data-titulo') || elemento.dataset.titulo;
    const descripcion = elemento.getAttribute('data-descripcion') || elemento.dataset.descripcion;

    console.log('🔍 Datos capturados:', { url, titulo, descripcion });

    if (!url) {
        console.error('❌ Error: No se proporcionó una URL para compartir.');
        return;
    }

    // 2. Formatear el texto estéticamente (Negritas compatibles con WhatsApp)
    const textoCompartir = `⭐ *${titulo}*\n${descripcion || ''}`;

    // 3. Evaluar compatibilidad con la API Nativa de Compartir (Móviles: Android / iOS)
    // Agregamos 'navigator.canShare' para garantizar que el navegador realmente acepte los datos
    if (navigator.share && navigator.canShare) {
        try {
            await navigator.share({
                title: titulo,
                text: textoCompartir,
                url: url
            });
            console.log('✅ Compartido de forma nativa exitosamente');
        } catch (error) {
            // Si el error no es porque el usuario canceló la acción, usamos el portapapeles como plan B
            if (error.name !== 'AbortError' && error.name !== 'NotAllowedError') {
                console.warn('⚠️ Falló Web Share API, reintentando con portapapeles:', error);
                await copiarAlPortapapeles(textoCompartir, url);
            } else {
                console.log('ℹ️ El usuario canceló la acción de compartir.');
            }
        }
    } else {
        // 4. Escritorio / PC / Navegadores no compatibles: Forzar copiado al portapapeles
        console.log('💻 Dispositivo de escritorio detectado');
        await copiarAlPortapapeles(textoCompartir, url);
    }
}

/**
 * Copia el texto formateado junto al enlace al portapapeles utilizando la API moderna
 */
async function copiarAlPortapapeles(texto, url) {
    const textoCompleto = `${texto}\n\n${url}`;
    
    if (navigator.clipboard && navigator.clipboard.writeText) {
        try {
            await navigator.clipboard.writeText(textoCompleto);
            alert('✅ ¡Detalles del producto y enlace copiados al portapapeles!');
            console.log('✅ Copiado usando API moderna');
        } catch (error) {
            console.error('❌ Falló la API moderna de portapapeles:', error);
            fallbackCopiar(textoCompleto);
        }
    } else {
        fallbackCopiar(textoCompleto);
    }
}

/**
 * Método alternativo de copiado para navegadores antiguos o entornos HTTP inseguros
 */
function fallbackCopiar(texto) {
    try {
        const textarea = document.createElement('textarea');
        textarea.value = texto;
        
        // Evitamos que haga scroll en pantallas móviles viejas al insertarlo
        textarea.style.position = 'fixed'; 
        textarea.style.top = '0';
        textarea.style.left = '0';
        textarea.style.opacity = '0';
        
        document.body.appendChild(textarea);
        textarea.focus();
        textarea.select();
        
        const resultado = document.execCommand('copy');
        document.body.removeChild(textarea);
        
        if (resultado) {
            alert('✅ ¡Detalles del producto copiados al portapapeles!');
            console.log('✅ Copiado usando fallback clásico');
        } else {
            throw new Error('execCommand devolvió false');
        }
    } catch (err) {
        console.error('❌ No se pudo copiar el texto de ninguna manera:', err);
        alert('❌ No se pudo copiar automáticamente. Por favor, copia el enlace manualmente.');
    }
}

console.log('🚀 módulo compartirProducto inicializado correctamente.');

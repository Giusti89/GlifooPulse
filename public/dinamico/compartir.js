async function compartirProducto(elemento) {
    const url = elemento.getAttribute('data-url') || elemento.dataset.url;
    const titulo = elemento.getAttribute('data-titulo') || elemento.dataset.titulo;
    const descripcion = elemento.getAttribute('data-descripcion') || elemento.dataset.descripcion;
    const imagen = elemento.getAttribute('data-imagen') || elemento.dataset.imagen;

    console.log('🔍 Compartiendo:', { url, titulo, descripcion, imagen });

    if (!url) {
        console.error('❌ Error: No se proporcionó una URL para compartir.');
        return;
    }

    const textoMensaje = `⭐ *${titulo}*\n${descripcion || ''}`;
    const textoCompleto = `${textoMensaje}\n\n${url}`;

    // ✅ 1. Intentar Web Share API (funciona en móviles y en algunos navegadores de escritorio)
    if (navigator.share) {
        try {
            await navigator.share({
                title: titulo,
                text: textoMensaje,
                url: url
            });
            console.log('✅ Compartido exitosamente');
            return;
        } catch (error) {
            if (error.name === 'AbortError') {
                console.log('ℹ️ Usuario canceló');
                return;
            }
            console.warn('⚠️ Falló Web Share, usando fallback');
        }
    }

    // ✅ 2. Fallback principal: Copiar al portapapeles (funciona en PC y móvil)
    try {
        await navigator.clipboard.writeText(textoCompleto);
        alert('✅ ¡Enlace copiado al portapapeles! Pégalo donde quieras (WhatsApp, Facebook, etc.)');
        console.log('✅ Copiado al portapapeles');
        return;
    } catch (error) {
        console.warn('⚠️ Falló clipboard, usando fallback de WhatsApp');
    }

    // ✅ 3. Último fallback: Abrir WhatsApp (solo si todo lo demás falla)
    const whatsappUrl = `https://api.whatsapp.com/send?text=${encodeURIComponent(textoCompleto)}`;
    window.open(whatsappUrl, '_blank');
}
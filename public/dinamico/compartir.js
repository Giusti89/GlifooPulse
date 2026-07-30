async function compartirProducto(elemento) {
    const url = elemento.getAttribute('data-url') || elemento.dataset.url;
    const titulo = elemento.getAttribute('data-titulo') || elemento.dataset.titulo;

    console.log('🔍 Compartiendo URL directa:', url);

    if (!url) return;

    // 📱 MÓVIL: Compartimos usando la API nativa de Android/iOS
    if (navigator.share && navigator.canShare) {
        try {
            await navigator.share({
                title: titulo,
                url: url // 👈 Al enviar solo la URL aquí, el menú nativo de WhatsApp genera la tarjeta perfecta
            });
            return;
        } catch (error) {
            if (error.name !== 'AbortError' && error.name !== 'NotAllowedError') {
                await copiarSoloUrl(url);
            }
        }
    } else {
        // 💻 PC: Copiar al portapapeles
        await copiarSoloUrl(url);
    }
}

async function copiarSoloUrl(url) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
        try {
            await navigator.clipboard.writeText(url); // 👈 Copiamos únicamente la URL limpia
            alert('✅ ¡Enlace del producto copiado al portapapeles! Al pegarlo en WhatsApp se generará la vista previa.');
        } catch (error) {
            fallbackCopiarRuta(url);
        }
    } else {
        fallbackCopiarRuta(url);
    }
}

function fallbackCopiarRuta(url) {
    const textarea = document.createElement('textarea');
    textarea.value = url;
    textarea.style.position = 'fixed';
    textarea.style.opacity = '0';
    document.body.appendChild(textarea);
    textarea.focus();
    textarea.select();
    document.execCommand('copy');
    document.body.removeChild(textarea);
    alert('✅ Enlace copiado.');
}
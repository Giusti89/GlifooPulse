function compartirProducto(boton) {
    // Obtener datos del botón (compatible con getAttribute)
    const url = boton.getAttribute('data-url') || boton.dataset.url;
    const titulo = boton.getAttribute('data-titulo') || boton.dataset.titulo;
    const descripcion = boton.getAttribute('data-descripcion') || boton.dataset.descripcion;
    const imagen = boton.getAttribute('data-imagen') || boton.dataset.imagen;

    // Texto formateado de manera atractiva para redes sociales
    const textoCompartir = `⭐ *${titulo}*\n${descripcion}`;
    // 1. Intentar compartir con la API Nativa (Móviles / Tabletas)
    if (navigator.share) {
        navigator.share({
            title: titulo,
            text: `${titulo} - ${descripcion}`,
            url: url
        })
        .then(() => console.log('✅ Compartido exitosamente'))
        .catch((error) => {
            console.log('❌ Error al compartir:', error);
            // Si el usuario cancela, no hacer nada
            if (error.name !== 'AbortError') {
                copiarAlPortapapeles(textoCompartir, url);
            }
        });
    } else {
        // 2. Comportamiento en Escritorio: Copiar al portapapeles
        copiarAlPortapapeles(textoCompartir, url);
    }
}

function copiarAlPortapapeles(texto, url) {
    const textoCompleto = `${texto}\n\nVer más detalles aquí: ${url}`;
    
    if (navigator.clipboard) {
        navigator.clipboard.writeText(textoCompleto)
            .then(() => {
                // Usa SweetAlert o un toast si tienes, si no usa alert
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Copiado!',
                        text: 'El enlace y detalles del producto se copiaron al portapapeles.',
                        timer: 3000,
                        showConfirmButton: false
                    });
                } else {
                    alert('¡Enlace y detalles del producto copiados al portapapeles!');
                }
            })
            .catch(() => {
                // Fallback: abrir WhatsApp Web
                abrirWhatsAppWeb(textoCompleto);
            });
    } else {
        // Fallback para navegadores antiguos
        fallbackCopiar(textoCompleto);
    }
}

function fallbackCopiar(texto) {
    const textarea = document.createElement('textarea');
    textarea.value = texto;
    document.body.appendChild(textarea);
    textarea.select();
    document.execCommand('copy');
    document.body.removeChild(textarea);
    alert('¡Enlace y detalles del producto copiados al portapapeles!');
}

function abrirWhatsAppWeb(texto) {
    const whatsappUrl = `https://web.whatsapp.com/send?text=${encodeURIComponent(texto)}`;
    window.open(whatsappUrl, '_blank');
}
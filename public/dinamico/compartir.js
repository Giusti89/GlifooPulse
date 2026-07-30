function compartirProducto(elemento) {
    // Capturamos los datos de los atributos HTML
    const url = elemento.dataset.url;
    const titulo = elemento.dataset.titulo;
    const descripcion = elemento.dataset.descripcion;
    // 🛠️ CORRECCIÓN: Leemos directamente .imagen para que coincida con data-imagen
    const imagen = elemento.dataset.imagen; 

    console.log('Compartiendo:', { url, titulo, descripcion, imagen });

    // Validar que la URL sea válida
    if (!url || !url.startsWith('http')) {
        console.error('❌ URL inválida:', url);
        alert('Error: La URL del producto no es válida');
        return;
    }

    // 1. Si el navegador soporta Web Share API (Dispositivos Móviles)
    if (navigator.share) {
        navigator.share({
            title: titulo,
            url: url // 👈 IMPORTANTE: Enviamos solo la URL para garantizar la tarjeta visual en WhatsApp
        })
        .then(() => console.log('✅ Compartido exitosamente'))
        .catch((error) => {
            if (error.name !== 'AbortError' && error.name !== 'NotAllowedError') {
                console.log('❌ Error al compartir, usando portapapeles:', error);
                copiarAlPortapapeles(url);
            }
        });
    } else {
        // 2. Si es PC / Escritorio: copiar al portapapeles
        console.log('ℹ️ Usando copiar al portapapeles');
        copiarAlPortapapeles(url);
    }
}

function copiarAlPortapapeles(url) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(url) // 👈 Copiamos únicamente el enlace limpio
            .then(() => {
                alert('¡Enlace del producto copiado al portapapeles! Al pegarlo en WhatsApp se generará la miniatura.');
            })
            .catch(() => {
                fallbackCopiar(url);
            });
    } else {
        fallbackCopiar(url);
    }
}

function fallbackCopiar(texto) {
    const textarea = document.createElement('textarea');
    textarea.value = texto;
    textarea.style.position = 'fixed';
    textarea.style.opacity = '0';
    document.body.appendChild(textarea);
    textarea.select();
    document.execCommand('copy');
    document.body.removeChild(textarea);
    alert('¡Enlace copiado al portapapeles!');
}
function compartirProducto(elemento) {
    const url = elemento.dataset.url;
    const titulo = elemento.dataset.titulo;
    const descripcion = elemento.dataset.descripcion;
    const imagen = elemento.dataset.imagen;

    // 1. Verificar si el navegador soporta Web Share API (móviles)
    if (navigator.share) {
        navigator.share({
            title: titulo,
            text: descripcion,
            url: url
        })
        .then(() => console.log('Compartido exitosamente'))
        .catch((error) => {
            console.log('Error al compartir:', error);
            // Si falla, caer en el método tradicional
            copiarAlPortapapeles(url, titulo);
        });
    } else {
        // 2. Fallback para PC: copiar al portapapeles
        copiarAlPortapapeles(url, titulo);
    }
}

function copiarAlPortapapeles(url, titulo) {
    const mensaje = `Mira este producto: ${titulo}\n${url}`;

    if (navigator.clipboard) {
        navigator.clipboard.writeText(mensaje)
            .then(() => {
                alert('¡Enlace copiado al portapapeles! Compártelo donde quieras.');
            })
            .catch(() => {
                fallbackCopiar(mensaje);
            });
    } else {
        fallbackCopiar(mensaje);
    }
}

function fallbackCopiar(texto) {
    const textarea = document.createElement('textarea');
    textarea.value = texto;
    document.body.appendChild(textarea);
    textarea.select();
    document.execCommand('copy');
    document.body.removeChild(textarea);
    alert('¡Enlace copiado al portapapeles! Compártelo donde quieras.');
}

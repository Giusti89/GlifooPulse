function compartirProducto(elemento) {
    // Obtener datos del botón
    const url = elemento.getAttribute('data-url') || elemento.dataset.url;
    const titulo = elemento.getAttribute('data-titulo') || elemento.dataset.titulo;
    const descripcion = elemento.getAttribute('data-descripcion') || elemento.dataset.descripcion;
    const imagen = elemento.getAttribute('data-imagen') || elemento.dataset.imagen;

    console.log('🔍 Compartiendo:', { url, titulo, descripcion, imagen });

    // Texto formateado para compartir
    const textoCompartir = `⭐ *${titulo}*\n${descripcion}`;

    // 🔥 PRUEBA: Alert para confirmar que la función se ejecuta
    // alert('✅ Botón funcionando!');

    // 1. Si el navegador soporta Web Share API (Móviles)
    if (navigator.share) {
        navigator.share({
            title: titulo,
            text: `${textoCompartir}`,
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
        // 2. Escritorio: Copiar al portapapeles
        console.log('💻 Usando método de escritorio');
        copiarAlPortapapeles(textoCompartir, url);
    }
}

function copiarAlPortapapeles(texto, url) {
    const textoCompleto = `${texto}\n\nVer más detalles aquí: ${url}`;
    
    if (navigator.clipboard) {
        navigator.clipboard.writeText(textoCompleto)
            .then(() => {
                // Usar alert o SweetAlert
                alert('✅ ¡Enlace y detalles copiados al portapapeles!');
                console.log('✅ Copiado al portapapeles');
            })
            .catch(() => {
                fallbackCopiar(textoCompleto);
            });
    } else {
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
    alert('✅ ¡Enlace copiado al portapapeles!');
}

// Verificar que la función está disponible
console.log('✅ compartirProducto cargada correctamente');

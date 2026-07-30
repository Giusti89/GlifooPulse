function compartirProducto(elemento) {
    // Obtener la URL del data-url (que apunta a share.blade.php)
    const url = elemento.dataset.url;  // 👈 Esta es la URL que se comparte
    const titulo = elemento.dataset.titulo;
    const descripcion = elemento.dataset.descripcion;
    const imagen = elemento.dataset.imagen;

    console.log('🔍 Compartiendo URL:', url);

    // Texto formateado
    const textoCompartir = `⭐ *${titulo}*\n${descripcion}`;

    // 1. Web Share API (Móviles) - Comparte la URL de share.blade.php
    if (navigator.share) {
        navigator.share({
            title: titulo,
            text: textoCompartir,
            url: url  // 👈 Se comparte la URL de share.blade.php
        })
        .catch((error) => {
            if (error.name !== 'AbortError') {
                copiarAlPortapapeles(textoCompartir, url);
            }
        });
    } else {
        // 2. Escritorio: Copiar al portapapeles
        copiarAlPortapapeles(textoCompartir, url);
    }
}
function compartirProducto(boton) {
    const urlSeo = boton.getAttribute('data-url-seo');
    const urlDestino = boton.getAttribute('data-url-destino');
    const titulo = boton.getAttribute('data-titulo');
    const htmlSucio = boton.getAttribute('data-descripcion');

    // Extraer slug del producto desde la URL SEO
    // urlSeo ej: https://glifoo.org/compartir/lamadriguera/taller-amigurumi
    const productSlug = urlSeo.split('/').filter(Boolean).pop() || 'producto';

    // ✅ NUEVO: construir URL con UTMs automáticos
    const urlConUtms = urlSeo + '?utm_source=share&utm_medium=button&utm_campaign=' + encodeURIComponent(productSlug);

    // Limpiar descripción HTML
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = htmlSucio;
    const descripcion = tempDiv.innerText || tempDiv.textContent || "";

    // 1. FLUJO PARA MÓVILES (API NATIVA)
    if (navigator.share) {
        const textoMovil = `⭐ *${titulo}*\n${descripcion}\n\n👉 Ver producto completo aquí:`;

        navigator.share({
            title: titulo,
            text: textoMovil,
            url: urlConUtms // ✅ URL con UTMs
        })
            .catch((error) => {
                if (error.name !== 'AbortError') {
                    console.log('Interrupción al compartir', error);
                }
            });

    } else {
        // 2. FLUJO PARA ESCRITORIO
        const textoEscritorio = `⭐ *${titulo}*\n${descripcion}\n\n👉 Ver producto completo aquí:\n${urlConUtms}`;

        navigator.clipboard.writeText(textoEscritorio)
            .then(() => {
                // ✅ Cambiado alert() por Swal (consistente)
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Copiado!',
                        position: 'top-end',
                        toast: true,
                        text: 'Detalles y enlace copiados. Abre WhatsApp y pégalo.',
                        timer: 2500,
                        showConfirmButton: false,
                        didOpen: (toast) => {
                            const container = Swal.getContainer();
                            if (container) container.style.zIndex = '999999';
                        }
                    });
                } else {
                    alert('¡Detalles y enlace del producto copiados! Abre WhatsApp y pégalo.');
                }
            })
            .catch(() => {
                // ✅ URL de WhatsApp corregida (falta ?text=)
                const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(textoEscritorio)}`;
                window.open(whatsappUrl, '_blank');
            });
    }
}

function abrirCompartirCatalogo() {
    const modal = document.getElementById('compartirCatalogoModal');
    const qrContainer = document.getElementById('qrCatalogo');

    if (!modal || !qrContainer) {
        return;
    }

    const urlCatalogo = window.location.href;
    const logoUrl = modal.dataset.logo;

    qrContainer.innerHTML = '';

    const qr = new QRCode(qrContainer, {
        text: urlCatalogo,
        width: 220,
        height: 220,
        colorDark: '#000000',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.H
    });

    const logo = document.createElement('img');

    logo.src = logoUrl;
    logo.alt = 'Logo';
    logo.className = 'qr-logo-overlay';

    qrContainer.appendChild(logo);

    modal.style.display = 'flex';
}

function cerrarCompartirCatalogo() {
    const modal = document.getElementById('compartirCatalogoModal');

    if (!modal) {
        return;
    }

    modal.style.display = 'none';
}

function copiarEnlaceCatalogo() {
    const urlCatalogo = window.location.href;

    navigator.clipboard.writeText(urlCatalogo)
        .then(() => {
            Swal.fire({
                icon: 'success',
                title: '¡Copiado!',
                position: 'top-end',
                toast: true,
                text: 'El enlace del catálogo se ha copiado al portapapeles.',
                timer: 2000,
                showConfirmButton: false,
                didOpen: (toast) => {
                    // 👈 Esto asegura que esté al frente de absolutamente todo (z-index alto)
                    const container = Swal.getContainer();
                    if (container) {
                        container.style.zIndex = '999999';
                    }
                }
            });
        })
        .catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo copiar el enlace.',
                position: 'top-end',
                toast: true,
                timer: 3000,
                showConfirmButton: false,
                didOpen: (toast) => {
                    const container = Swal.getContainer();
                    if (container) {
                        container.style.zIndex = '999999';
                    }
                }
            });

        });
}

function compartirCatalogo() {
    const urlCatalogo = window.location.href;
    const titulo = document.title;

    if (navigator.share) {
        navigator.share({
            title: titulo,
            text: 'Visita nuestro catálogo:',
            url: urlCatalogo
        }).catch((error) => {
            console.log('Interrupción al compartir:', error);
        });

        return;
    }

    navigator.clipboard.writeText(urlCatalogo)
        .then(() => {
            Swal.fire({
                icon: 'success',
                title: '¡Copiado!',
                position: 'top-end',
                toast: true,
                text: 'El enlace del catálogo se ha copiado al portapapeles.',
                timer: 2000,
                showConfirmButton: false,
                didOpen: (toast) => {
                    // 👈 Esto asegura que esté al frente de absolutamente todo (z-index alto)
                    const container = Swal.getContainer();
                    if (container) {
                        container.style.zIndex = '999999';
                    }
                }
            });
        })
        .catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo copiar el enlace.',
                position: 'top-end',
                toast: true,
                timer: 3000,
                showConfirmButton: false,
                didOpen: (toast) => {
                    const container = Swal.getContainer();
                    if (container) {
                        container.style.zIndex = '999999';
                    }
                }
            });
        });
}
function descargarQrCatalogo() {
    const urlCatalogo = window.location.href;
    const modal = document.getElementById('compartirCatalogoModal');

    if (!modal) {
        return;
    }

    const logoUrl = modal.dataset.logo;

    // Creamos un QR independiente y de mayor resolución
    const contenedor = document.createElement('div');

    const qr = new QRCode(contenedor, {
        text: urlCatalogo,
        width: 1000,
        height: 1000,
        colorDark: '#000000',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.H
    });

    // Esperamos a que QRCode.js genere la imagen
    setTimeout(() => {

        const qrImg = contenedor.querySelector('img');

        if (!qrImg) {
            alert('No se pudo generar el código QR.');
            return;
        }

        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');

        canvas.width = 1000;
        canvas.height = 1000;

        // Fondo blanco
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, 1000, 1000);

        // Dibujamos el QR
        const qrImage = new Image();

        qrImage.onload = () => {

            ctx.drawImage(qrImage, 0, 0, 1000, 1000);

            // Logo centrado
            if (logoUrl) {

                const logo = new Image();

                logo.onload = () => {

                    const logoSize = 180;
                    const logoX = (1000 - logoSize) / 2;
                    const logoY = (1000 - logoSize) / 2;

                    // Fondo blanco detrás del logo
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(
                        logoX - 20,
                        logoY - 20,
                        logoSize + 40,
                        logoSize + 40
                    );

                    ctx.drawImage(
                        logo,
                        logoX,
                        logoY,
                        logoSize,
                        logoSize
                    );

                    descargarCanvasQr(canvas);
                };

                logo.onerror = () => {
                    descargarCanvasQr(canvas);
                };

                logo.src = logoUrl;

            } else {
                descargarCanvasQr(canvas);
            }
        };

        qrImage.src = qrImg.src;

    }, 100);
}

function descargarCanvasQr(canvas) {
    const enlace = document.createElement('a');

    enlace.download = 'qr-catalogo.png';
    enlace.href = canvas.toDataURL('image/png');

    enlace.click();
}

function compartirCategoriaActiva() {
    // 1. Encontrar la pestaña de categoría activa
    const tabActiva = document.querySelector('.categoria-tab.active');
    if (!tabActiva) {
        alert('No se pudo detectar ninguna categoría activa.');
        return;
    }

    // 2. Extraer el nombre estético y el identificador slug
    const nombreCategoria = tabActiva.querySelector('.tab-text').innerText.trim();
    const idContenedor = tabActiva.getAttribute('data-tab'); // Ej: "categoria-calzado-hombre"
    const categoriaSlug = idContenedor.replace('categoria-', ''); // Ej: "calzado-hombre"

    // 3. Construir la URL limpia usando el parámetro slug
    const urlBase = window.location.origin + window.location.pathname;
    const urlDestino = `${urlBase}?cat=${categoriaSlug}&utm_source=share&utm_medium=button&utm_campaign=categoria_${categoriaSlug}#${idContenedor}`;

    const titulo = `Categoría: ${nombreCategoria}`;
    const descripcion = `Mira todas nuestras soluciones y productos en la categoría ${nombreCategoria}.`;



    // 4. Compartir (Móvil)
    if (navigator.share) {
        const textoMovil = ` *${nombreCategoria}*\n${descripcion}\n\n Ver categoría completa aquí:`;
        navigator.share({
            title: titulo,
            text: textoMovil,
            url: urlDestino
        })
            .catch((error) => console.log('Interrupción al compartir categoría', error));
    } else {
        // 5. Compartir (Escritorio / Portapapeles con Swal)
        const textoEscritorio = ` *${nombreCategoria}*\n${descripcion}\n\n Ver categoría completa aquí:\n${urlDestino}`;

        navigator.clipboard.writeText(textoEscritorio).then(() => {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: '¡Copiado!',
                    position: 'top-end',
                    toast: true,
                    text: '¡Enlace de la categoría copiado al portapapeles!',
                    timer: 2500,
                    showConfirmButton: false,
                    didOpen: (toast) => {
                        const container = Swal.getContainer();
                        if (container) container.style.zIndex = '999999';
                    }
                });
            } else {
                alert('¡Enlace de la categoría copiado! Abre WhatsApp y pégalo.');
            }
        }).catch(err => {
            const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(textoEscritorio)}`;
            window.open(whatsappUrl, '_blank');
        });
    }
}

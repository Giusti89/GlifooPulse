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
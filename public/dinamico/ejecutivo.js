document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.categoria-tab');
    const contents = document.querySelectorAll('.categoria-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Remover active de todos
            tabs.forEach(t => t.classList.remove('active'));
            contents.forEach(c => c.classList.remove('active'));

            // Agregar active al seleccionado
            tab.classList.add('active');
            const tabId = tab.getAttribute('data-tab');
            document.getElementById(tabId).classList.add('active');
        });
    });

    // Scroll horizontal para tabs
    const scrollContainer = document.querySelector('.categorias-lista');
    const scrollLeft = document.getElementById('scrollLeft');
    const scrollRight = document.getElementById('scrollRight');

    scrollLeft?.addEventListener('click', () => {
        scrollContainer.scrollBy({ left: -200, behavior: 'smooth' });
    });

    scrollRight?.addEventListener('click', () => {
        scrollContainer.scrollBy({ left: 200, behavior: 'smooth' });
    });

    // Modal functionality
    const modal = document.getElementById('imagenModal');
    const modalImg = document.getElementById('modalImagen');
    const modalTitle = document.getElementById('modalTitulo');
    const closeModal = document.querySelector('.modal-cerrar');

    window.abrirModal = function (src, title) {
        modal.style.display = 'block';
        modalImg.src = src;
        modalTitle.textContent = title;
    }

    closeModal.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
});

// Función para WhatsApp (mantener tu función existente)
function abrirConsulta(imagenSrc, nombreProducto, productoId) {
    document.getElementById('modalProductoNombre').innerText = nombreProducto;
    document.getElementById('modalProductoImagen').src = imagenSrc;
    document.getElementById('productoId').value = productoId;

    const form = document.getElementById('consultaForm');
    form.action = `/consulta/${productoId}`;

    document.getElementById('consultaModal').style.display = 'flex';
}

function cerrarConsulta() {
    document.getElementById('consultaModal').style.display = 'none';
    document.getElementById('consultaForm').reset();

    document.getElementById('productoId').value = '';
    document.getElementById('nombre').value = '';
    document.getElementById('telefono').value = '';
    document.getElementById('mensaje').value = '';
}

// Cerrar modal al hacer clic fuera del contenido
window.onclick = function (event) {
    const modal = document.getElementById('consultaModal');
    if (event.target === modal) {
        cerrarModal();
    }
}
document.addEventListener("DOMContentLoaded", function () {
    const hash = window.location.hash;
    const urlParams = new URLSearchParams(window.location.search);
    const prodParam = urlParams.get('prod');
    // 1. Verificar si la URL trae un ancla de producto válida
    if ((hash && hash.startsWith('#prod-')) || prodParam) {
        const targetId = hash || `#prod-${prodParam}`;

        // Esperamos 250ms a que el DOM y el CSS se asienten perfectamente
        setTimeout(() => {
            const elementoProducto = document.querySelector(targetId);

            if (elementoProducto) {
                // 2. DETECTAR Y ACTIVAR LA CATEGORÍA PADRE OOCULTA
                // Buscamos el contenedor '.categoria-content' más cercano hacia arriba
                const contenedorCategoria = elementoProducto.closest('.categoria-content');

                if (contenedorCategoria && !contenedorCategoria.classList.contains('active')) {
                    // Desactivamos la categoría activa actual y activamos la correcta
                    document.querySelectorAll('.categoria-content').forEach(c => c.classList.remove('active'));
                    contenedorCategoria.classList.add('active');

                    // Sincronizamos los botones de las pestañas (Tabs de navegación)
                    const categoriaId = contenedorCategoria.getAttribute('id');
                    document.querySelectorAll('.categoria-tab').forEach(t => {
                        t.classList.remove('active');
                        if (t.getAttribute('data-tab') === categoriaId) {
                            t.classList.add('active');
                        }
                    });
                }

                // 3. DESPLAZAMIENTO FLUIDO AL PRODUCTO
                elementoProducto.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });

                // 4. EXTRAER DATOS Y ABRIR TU MODAL NATIVO
                // Obtenemos la imagen y el título directamente de los elementos de la tarjeta
                const imgElement = elementoProducto.querySelector('.servicio-imagen');
                const titleElement = elementoProducto.querySelector('.servicio-nombre');

                if (imgElement && titleElement && typeof window.abrirModal === 'function') {
                    window.abrirModal(imgElement.src, titleElement.textContent.trim());
                }
            }
        }, 250);
    }
});
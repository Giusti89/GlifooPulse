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
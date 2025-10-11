document.addEventListener('DOMContentLoaded', () => {
  const nav = document.querySelector('.categorias-navegacion');
  if (nav) {
    let isDown = false;
    let startX;
    let scrollLeft;

    nav.addEventListener('mousedown', (e) => {
      isDown = true;
      nav.classList.add('dragging');
      startX = e.pageX - nav.offsetLeft;
      scrollLeft = nav.scrollLeft;
    });

    nav.addEventListener('mouseup', () => {
      isDown = false;
      nav.classList.remove('dragging');
    });

    nav.addEventListener('mouseleave', () => {
      isDown = false;
      nav.classList.remove('dragging');
    });

    nav.addEventListener('mousemove', (e) => {
      if (!isDown) return;
      e.preventDefault();
      const x = e.pageX - nav.offsetLeft;
      const walk = (x - startX) * 1.5;
      nav.scrollLeft = scrollLeft - walk;
    });
  }

  // ——— Aquí añadimos el scroll suave + history.pushState ———
  document.querySelectorAll('.categoria-link').forEach(link => {
    link.addEventListener('click', function (e) {
      e.preventDefault();

      const slug = this.getAttribute('href').split('#')[1];
      const target = document.getElementById(slug);
      if (!target) return;

      // scroll suave
      target.scrollIntoView({ behavior: 'smooth' });

      // actualiza la URL en el navegador sin recargar
      const newUrl = `${window.location.pathname}#${slug}`;
      history.pushState(null, '', newUrl);
    });
  });
});

function abrirModal(src, titulo) {
  const modal = document.getElementById('modalImagen');
  const imagenModal = document.getElementById('imagenModal');
  const tituloModal = document.getElementById('tituloModal');

  imagenModal.src = src;
  tituloModal.textContent = titulo;
  modal.style.display = 'block';

  // Cerrar modal con ESC
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      cerrarModal();
    }
  });
}

function cerrarModal() {
  const modal = document.getElementById('modalImagen');
  modal.style.display = 'none';
}

// Cerrar modal al hacer click fuera de la imagen
document.getElementById('modalImagen').addEventListener('click', function (e) {
  if (e.target === this) {
    cerrarModal();
  }
});
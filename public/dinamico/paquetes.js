document.addEventListener('DOMContentLoaded', function () {
    // Generar partículas para cada tarjeta
    document.querySelectorAll('.tarjeta__particulas').forEach(container => {
        for (let i = 0; i < 15; i++) {
            const particula = document.createElement('div');
            particula.className = 'particula';
            particula.style.left = Math.random() * 100 + '%';
            particula.style.top = Math.random() * 100 + '%';
            particula.style.animationDelay = Math.random() * 3 + 's';
            particula.style.opacity = Math.random() * 0.7 + 0.3;
            container.appendChild(particula);
        }
    });

    // Efecto de parallax en las imágenes de fondo
    const tarjetas = document.querySelectorAll('.tarjeta[style*="background-image"]');

    tarjetas.forEach(tarjeta => {
        tarjeta.addEventListener('mousemove', function (e) {
            const x = e.clientX / window.innerWidth;
            const y = e.clientY / window.innerHeight;

            this.style.backgroundPosition = `${x * 20}px ${y * 20}px`;
        });

        tarjeta.addEventListener('mouseleave', function () {
            this.style.backgroundPosition = 'center';
        });
    });
});

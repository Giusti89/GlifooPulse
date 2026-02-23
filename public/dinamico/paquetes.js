// Función para generar partículas
function generarParticulas(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;
    
    const numParticulas = 20; // Número de partículas
    
    for (let i = 0; i < numParticulas; i++) {
        const particula = document.createElement('div');
        particula.className = 'particula';
        
        // Posición aleatoria
        const x = Math.random() * 100;
        const y = Math.random() * 100;
        
        // Tamaño aleatorio
        const size = Math.random() * 6 + 2; // 2-8px
        
        // Retraso de animación aleatorio
        const delay = Math.random() * 2;
        
        particula.style.cssText = `
            left: ${x}%;
            top: ${y}%;
            width: ${size}px;
            height: ${size}px;
            animation-delay: ${delay}s;
            opacity: ${Math.random() * 0.5 + 0.3};
        `;
        
        container.appendChild(particula);
    }
}



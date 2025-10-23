const canvas = document.getElementById('starfield');
const ctx = canvas.getContext('2d');
let stars = [];

canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

class Star {
    constructor() {
        this.x = Math.random() * canvas.width;
        this.y = Math.random() * canvas.height;
        this.z = Math.random() * 1000;
        this.prevX = this.x;
        this.prevY = this.y;
    }

    update() {
        this.z -= 2;
        if (this.z <= 0) {
            this.x = Math.random() * canvas.width;
            this.y = Math.random() * canvas.height;
            this.z = 1000;
            this.prevX = this.x;
            this.prevY = this.y;
        }

        this.prevX = this.x;
        this.prevY = this.y;

        this.x = (this.x - canvas.width / 2) * (1000 / this.z) + canvas.width / 2;
        this.y = (this.y - canvas.height / 2) * (1000 / this.z) + canvas.height / 2;
    }

    draw() {
        const opacity = Math.max(0, 1 - this.z / 1000);
        const size = Math.max(0, (1000 - this.z) / 1000 * 3);

        ctx.save();
        ctx.globalAlpha = opacity;
        ctx.beginPath();
        ctx.arc(this.x, this.y, size, 0, Math.PI * 2);
        ctx.fillStyle = '#f8fafc';
        ctx.fill();

        // Draw trail
        if (size > 1) {
            ctx.beginPath();
            ctx.moveTo(this.prevX, this.prevY);
            ctx.lineTo(this.x, this.y);
            ctx.strokeStyle = '#8b5cf6';
            ctx.lineWidth = size * 0.5;
            ctx.stroke();
        }
        ctx.restore();
    }
}

function initStars() {
    stars = [];
    for (let i = 0; i < 800; i++) {
        stars.push(new Star());
    }
}

function animate() {
    ctx.fillStyle = 'rgba(15, 15, 35, 0.1)';
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    stars.forEach(star => {
        star.update();
        star.draw();
    });

    requestAnimationFrame(animate);
}

// Create floating cosmic particles
function createCosmicParticles() {
    for (let i = 0; i < 15; i++) {
        const particle = document.createElement('div');
        particle.className = 'cosmic-particle';
        particle.style.width = Math.random() * 6 + 2 + 'px';
        particle.style.height = particle.style.width;
        particle.style.left = Math.random() * 100 + '%';
        particle.style.top = Math.random() * 100 + '%';
        particle.style.animationDelay = Math.random() * 8 + 's';
        particle.style.animationDuration = Math.random() * 4 + 6 + 's';
        document.body.appendChild(particle);
    }
}

// Mission Tabs Functionality
const missionTabs = document.querySelectorAll('.mission-tab');
const missionContents = document.querySelectorAll('.mission-content');

missionTabs.forEach(tab => {
    tab.addEventListener('click', () => {
        // Remove active class from all tabs and contents
        missionTabs.forEach(t => t.classList.remove('active'));
        missionContents.forEach(c => c.classList.remove('active'));

        // Add active class to clicked tab
        tab.classList.add('active');

        // Show corresponding content
        const tabId = tab.getAttribute('data-tab');
        document.getElementById(tabId).classList.add('active');
    });
});

// Initialize animations
initStars();
animate();
createCosmicParticles();

// Handle window resize
window.addEventListener('resize', () => {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    initStars();
});

// Mobile menu toggle
const mobileToggle = document.getElementById('mobile-toggle');
const navMenu = document.getElementById('nav-menu');

mobileToggle.addEventListener('click', () => {
    mobileToggle.classList.toggle('active');
    navMenu.classList.toggle('active');
});

// Close mobile menu when clicking on a link
document.querySelectorAll('.nav-menu a').forEach(link => {
    link.addEventListener('click', () => {
        mobileToggle.classList.remove('active');
        navMenu.classList.remove('active');
    });
});

// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});

// Navbar scroll effect
window.addEventListener('scroll', () => {
    const navbar = document.getElementById('navbar');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }

    // Fade in sections
    const sections = document.querySelectorAll('.fade-in');
    sections.forEach(section => {
        const rect = section.getBoundingClientRect();
        if (rect.top < window.innerHeight * 0.8) {
            section.classList.add('visible');
        }
    });
});

// Active menu highlighting based on scroll position
const sections = document.querySelectorAll('section');
const navLinks = document.querySelectorAll('nav ul a');

// Create an Intersection Observer
const observerOptions = {
    root: null, // viewport is the root
    rootMargin: '-30% 0px -70% 0px', // consider section visible when it's 30% from the top and 70% from the bottom
    threshold: 0
};

function onIntersect(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            // Get the id of the section that's in view
            const activeId = entry.target.getAttribute('id');

            // Remove active class from all links
            navLinks.forEach(link => {
                link.classList.remove('active');
            });

            // Add active class to the link that corresponds to the section in view
            const activeLink = document.querySelector(`nav ul a[href="#${activeId}"]`);
            if (activeLink) {
                activeLink.classList.add('active');
            }
        }
    });
}

const observer = new IntersectionObserver(onIntersect, observerOptions);

// Observe all sections
sections.forEach(section => {
    observer.observe(section);
});

// Set home as active by default when page loads
document.addEventListener('DOMContentLoaded', () => {
    navLinks[0].classList.add('active');
});

// Update active state when clicking on navigation links
navLinks.forEach(link => {
    link.addEventListener('click', function () {
        navLinks.forEach(l => l.classList.remove('active'));
        this.classList.add('active');
    });
});
// ------------modal--------------
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
document.addEventListener('DOMContentLoaded', function() {
    const stickyTabsContainer = document.querySelector('.sticky-tabs-container');
    const tabsScroll = document.getElementById('stickyTabs');
    const scrollLeft = document.getElementById('scrollLeft');
    const scrollRight = document.getElementById('scrollRight');
    const missionTabs = document.querySelectorAll('.mission-tab');
    const missionContents = document.querySelectorAll('.mission-content');

    // Scroll horizontal con indicadores
    function updateScrollIndicators() {
        if (!tabsScroll) return;
        
        const scrollableWidth = tabsScroll.scrollWidth - tabsScroll.clientWidth;
        const isAtStart = tabsScroll.scrollLeft <= 0;
        const isAtEnd = tabsScroll.scrollLeft >= scrollableWidth - 1; // -1 para margen de error

        if (scrollLeft) {
            scrollLeft.style.opacity = isAtStart ? '0.3' : '1';
            scrollLeft.style.cursor = isAtStart ? 'default' : 'pointer';
        }
        
        if (scrollRight) {
            scrollRight.style.opacity = isAtEnd ? '0.3' : '1';
            scrollRight.style.cursor = isAtEnd ? 'default' : 'pointer';
        }
    }

    // Scroll handlers
    if (scrollLeft) {
        scrollLeft.addEventListener('click', () => {
            tabsScroll.scrollBy({ left: -200, behavior: 'smooth' });
        });
    }

    if (scrollRight) {
        scrollRight.addEventListener('click', () => {
            tabsScroll.scrollBy({ left: 200, behavior: 'smooth' });
        });
    }

    // Actualizar indicadores al scroll
    if (tabsScroll) {
        tabsScroll.addEventListener('scroll', updateScrollIndicators);
    }

    // Sticky effect
    function handleStickyScroll() {
        if (window.scrollY > 100) {
            stickyTabsContainer.classList.add('scrolled');
        } else {
            stickyTabsContainer.classList.remove('scrolled');
        }
    }

    // Switch tab function
    function switchTab(tabId) {
        // Remover active de todos
        missionTabs.forEach(tab => tab.classList.remove('active'));
        missionContents.forEach(content => content.classList.remove('active'));
        
        // Activar tab seleccionado
        const activeTab = document.querySelector(`[data-tab="${tabId}"]`);
        const activeContent = document.getElementById(tabId);
        
        if (activeTab) activeTab.classList.add('active');
        if (activeContent) activeContent.classList.add('active');
        
        // Scroll suave a la sección
        if (activeContent) {
            activeContent.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start',
                inline: 'nearest'
            });
        }
    }

    // Tab click handlers
    missionTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            switchTab(tabId);
        });
    });

    // Intersection Observer para cambiar tabs automáticamente al scroll
    const observerOptions = {
        root: null,
        rootMargin: '-20% 0px -70% 0px', // Ajusta según necesidad
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const contentId = entry.target.id;
                const correspondingTab = document.querySelector(`[data-tab="${contentId}"]`);
                
                if (correspondingTab && !correspondingTab.classList.contains('active')) {
                    switchTab(contentId);
                    
                    // Scroll horizontal para mostrar el tab activo
                    const tabElement = correspondingTab;
                    const container = tabsScroll;
                    const tabLeft = tabElement.offsetLeft;
                    const tabWidth = tabElement.offsetWidth;
                    const containerWidth = container.clientWidth;
                    
                    container.scrollTo({
                        left: tabLeft - (containerWidth - tabWidth) / 2,
                        behavior: 'smooth'
                    });
                }
            }
        });
    }, observerOptions);

    // Observar todas las secciones de contenido
    missionContents.forEach(content => {
        observer.observe(content);
    });

    // Inicializar
    updateScrollIndicators();
    window.addEventListener('scroll', handleStickyScroll);
    window.addEventListener('resize', updateScrollIndicators);

    // Inicializar con el primer tab activo
    const firstTab = document.querySelector('.mission-tab.active');
    if (firstTab) {
        const firstTabId = firstTab.getAttribute('data-tab');
        switchTab(firstTabId);
    }
});
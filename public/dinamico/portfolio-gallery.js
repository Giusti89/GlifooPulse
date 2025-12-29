document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const galleryItems = document.querySelectorAll('.masonry-item');
    const imageInfoBar = document.getElementById('imageInfoBar');
    const currentTitle = document.getElementById('currentImageTitle');
    const currentDesc = document.getElementById('currentImageDesc');
    const currentIndex = document.getElementById('currentImageIndex');
    const totalImages = document.getElementById('totalImages');
    
    // Modal elements
    const imageModal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const modalImageTitle = document.getElementById('modalImageTitle');
    const modalImageDesc = document.getElementById('modalImageDesc');
    const modalOverlay = document.getElementById('modalOverlay');
    const modalClose = document.getElementById('modalClose');
    const prevImageBtn = document.getElementById('prevImage');
    const nextImageBtn = document.getElementById('nextImage');
    
    let currentModalIndex = 0;
    const totalGalleryItems = galleryItems.length;
    
    // Inicializar contador
    totalImages.textContent = totalGalleryItems;
    
    // Configurar eventos para cada imagen de la galería
    galleryItems.forEach((item, index) => {
        // Hover para mostrar info en barra inferior
        item.addEventListener('mouseenter', () => {
            const title = item.dataset.title || 'Imagen ' + (index + 1);
            const desc = item.dataset.desc || '';
            
            currentTitle.textContent = title;
            currentDesc.textContent = desc;
            currentIndex.textContent = index + 1;
            
            imageInfoBar.classList.add('active');
        });
        
        item.addEventListener('mouseleave', () => {
            // Ocultar barra después de un retraso
            setTimeout(() => {
                if (!imageInfoBar.matches(':hover')) {
                    imageInfoBar.classList.remove('active');
                }
            }, 300);
        });
        
        // Click para abrir modal
        item.addEventListener('click', () => {
            openModal(index);
        });
    });
    
    // Mantener barra visible cuando el mouse está sobre ella
    imageInfoBar.addEventListener('mouseenter', () => {
        imageInfoBar.classList.add('active');
    });
    
    imageInfoBar.addEventListener('mouseleave', () => {
        setTimeout(() => {
            imageInfoBar.classList.remove('active');
        }, 300);
    });
    
    // Funciones del modal
    function openModal(index) {
        currentModalIndex = index;
        updateModalContent();
        imageModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeModal() {
        imageModal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
    
    function updateModalContent() {
        const item = galleryItems[currentModalIndex];
        const img = item.querySelector('img');
        const title = item.dataset.title || 'Imagen ' + (currentModalIndex + 1);
        const desc = item.dataset.desc || '';
        
        modalImage.src = img.dataset.full || img.src;
        modalImage.alt = img.alt;
        modalImageTitle.textContent = title;
        modalImageDesc.textContent = desc;
        
        // Actualizar contador
        currentIndex.textContent = currentModalIndex + 1;
    }
    
    function showNextImage() {
        currentModalIndex = (currentModalIndex + 1) % totalGalleryItems;
        updateModalContent();
    }
    
    function showPrevImage() {
        currentModalIndex = (currentModalIndex - 1 + totalGalleryItems) % totalGalleryItems;
        updateModalContent();
    }
    
    // Event listeners del modal
    modalOverlay.addEventListener('click', closeModal);
    modalClose.addEventListener('click', closeModal);
    nextImageBtn.addEventListener('click', showNextImage);
    prevImageBtn.addEventListener('click', showPrevImage);
    
    // Navegación con teclado
    document.addEventListener('keydown', (e) => {
        if (!imageModal.classList.contains('active')) return;
        
        switch(e.key) {
            case 'Escape':
                closeModal();
                break;
            case 'ArrowRight':
                showNextImage();
                break;
            case 'ArrowLeft':
                showPrevImage();
                break;
        }
    });
    
    // Swipe para móviles
    let touchStartX = 0;
    let touchEndX = 0;
    
    modalImage.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });
    
    modalImage.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, { passive: true });
    
    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;
        
        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                showNextImage();
            } else {
                showPrevImage();
            }
        }
    }
    
    // Inicializar masonry con imágenes cargadas
    function initMasonry() {
        const images = document.querySelectorAll('.gallery-image');
        let loadedCount = 0;
        
        images.forEach(img => {
            if (img.complete) {
                loadedCount++;
            } else {
                img.addEventListener('load', () => {
                    loadedCount++;
                    if (loadedCount === images.length) {
                        adjustMasonry();
                    }
                });
            }
        });
        
        if (loadedCount === images.length) {
            adjustMasonry();
        }
    }
    
    function adjustMasonry() {
        // Ajustar columnas basado en el tamaño de las imágenes
        const gallery = document.getElementById('portfolioGallery');
        const items = gallery.querySelectorAll('.masonry-item');
        
        items.forEach(item => {
            const img = item.querySelector('img');
            if (img.naturalWidth > img.naturalHeight * 1.5) {
                // Imagen horizontal
                item.style.columnSpan = 'all';
            } else {
                item.style.columnSpan = 'none';
            }
        });
    }
    
    // Inicializar
    initMasonry();
    
    // Redimensionar ventana
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(initMasonry, 250);
    });
    
    // Precargar imágenes para el modal
    function preloadModalImages() {
        galleryItems.forEach(item => {
            const img = new Image();
            const originalImg = item.querySelector('img');
            img.src = originalImg.dataset.full || originalImg.src;
        });
    }
    
    // Precargar después de que la página cargue
    window.addEventListener('load', preloadModalImages);
});
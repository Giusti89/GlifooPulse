let player;
let currentIndex = 0;

function initPlayer() {
    const videos = window.__TV_VIDEOS || [];
    if (!videos.length) return;

    loadVideo(currentIndex);
}

function onYouTubeIframeAPIReady() {
    initPlayer();
}

// ✅ Si la API ya estaba cargada (refresh móvil), inicializamos manualmente
if (window.YT && window.YT.Player) {
    initPlayer();
}

function extractVideoId(url) {
    const match = url.match(/(?:v=|youtu\.be\/|embed\/)([^&?/]+)/);
    return match ? match[1] : null;
}

function loadVideo(index) {
    const videos = window.__TV_VIDEOS;
    const videoId = extractVideoId(videos[index]);

    if (!videoId) return;

    if (!player) {
        player = new YT.Player('player', {
            videoId: videoId,
            playerVars: {
                autoplay: 1,
                mute: 1,
                controls: 1,
                modestbranding: 1,
                rel: 0,
                playsinline: 1,
                enablejsapi: 1,
                origin: window.location.origin
            },
            events: {
                onReady: onPlayerReady, // Modificado aquí
                onStateChange: onVideoStateChange
            }
        });
    } else {
        player.loadVideoById({
            videoId: videoId,
            startSeconds: 0
        });
    }
}

function onPlayerReady(event) {
    event.target.mute();

    const playPromise = event.target.playVideo();

    if (playPromise && typeof playPromise.catch === 'function') {
        playPromise.catch(() => {
            console.log("El navegador bloqueó el autoplay. Esperando interacción del usuario...");
    
        });
    }
}


function onVideoStateChange(event) {
    const ENDED = 0;

    if (event.data === ENDED) {
        goToNextVideo();
    }
}

function goToNextVideo() {
    const videos = window.__TV_VIDEOS;
    currentIndex = (currentIndex + 1) % videos.length;
    loadVideo(currentIndex);
}
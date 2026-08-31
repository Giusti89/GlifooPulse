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
    const match = url.match(
        /(?:v=|youtu\.be\/|embed\/|shorts\/)([^&?/]+)/
    );

    return match ? match[1] : null;
}

function loadVideo(index) {

    const videos = window.__TV_VIDEOS;

    console.log('🎬 loadVideo()');
    console.log('Índice recibido:', index);
    console.log('URL recibida:', videos[index]);

    const videoId = extractVideoId(videos[index]);

    console.log('ID de YouTube:', videoId);

    if (!videoId) {
        console.error('❌ No se pudo obtener el ID del video');
        return;
    }

    if (!player) {

        console.log('🆕 Creando reproductor');

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
                onReady: (e) => e.target.playVideo(),
                onStateChange: onVideoStateChange
            }
        });

    } else {

        console.log('🔄 Cargando nuevo video:', videoId);

        player.loadVideoById(videoId);
    }
}

function onVideoStateChange(event) {

    console.log('=================================');
    console.log('Estado del reproductor:', event.data);

    const ENDED = YT.PlayerState.ENDED;

    if (event.data === ENDED) {

        console.log('🔥🔥🔥 VIDEO TERMINADO 🔥🔥🔥');

        const videos = window.__TV_VIDEOS;

        console.log('Cantidad de videos:', videos.length);
        console.log('Índice actual:', currentIndex);
        console.log('Videos:', videos);

        if (videos.length === 1) {

            console.log('🔄 Solo hay un video, reiniciando');

            player.playVideo();

        } else {

            console.log('➡️ Hay varios videos, pasando al siguiente');

            goToNextVideo();
        }
    }
}

function goToNextVideo() {

    const videos = window.__TV_VIDEOS;

    console.log('🚀 EJECUTANDO goToNextVideo()');

    currentIndex = (currentIndex + 1) % videos.length;

    console.log('Nuevo índice:', currentIndex);
    console.log('Nuevo video:', videos[currentIndex]);

    loadVideo(currentIndex);
}
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
                onReady: (e) => e.target.playVideo(),
                onStateChange: onVideoStateChange
            }
        });
    } else {
        player.loadVideoById(videoId);
    }
}

function onVideoStateChange(event) {
    console.log('Estado del reproductor:', event.data);
    const ENDED = YT.PlayerState.ENDED;
    if (event.data === ENDED) {
        const videos = window.__TV_VIDEOS;
        if (videos.length === 1) {
            player.playVideo(); // reinicia el mismo video
        } else {
            goToNextVideo();
        }
    }
}

function goToNextVideo() {
    const videos = window.__TV_VIDEOS;
    currentIndex = (currentIndex + 1) % videos.length;
    loadVideo(currentIndex);
}
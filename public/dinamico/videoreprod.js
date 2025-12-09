let player;
let currentIndex = 0;

function onYouTubeIframeAPIReady() {
    const videos = window.__TV_VIDEOS || [];

    if (videos.length === 0) {
        console.warn("No hay videos para reproducir.");
        return;
    }

    loadVideo(currentIndex);
}

function extractVideoId(url) {
    const match = url.match(/(?:v=|youtu\.be\/|embed\/)([^&?/]+)/);
    return match ? match[1] : null;
}

function loadVideo(index) {
    const videos = window.__TV_VIDEOS;

    const videoId = extractVideoId(videos[index]);

    if (!videoId) {
        console.error("No se pudo extraer el ID del video:", videos[index]);
        return;
    }

    if (!player) {
        player = new YT.Player('player', {
            videoId: videoId,
            playerVars: {
                autoplay: 1,
                controls: 0,
                modestbranding: 1,
                rel: 0,
                showinfo: 0,
                iv_load_policy: 3,
                disablekb: 1,
                playsinline: 1
            },
            events: {
                'onReady': onVideoReady,
                'onStateChange': onVideoStateChange
            }
        });
    } else {
        player.loadVideoById(videoId);
    }
}

function onVideoReady(event) {
    event.target.playVideo();
}

function onVideoStateChange(event) {
    const YT_ENDED = 0;
    const YT_PLAYING = 1;

    if (event.data === YT_PLAYING) {
        const duration = player.getDuration();
        const maxDuration = 90; // 1:30 minutos

        if (duration > maxDuration) {
            setTimeout(() => {
                goToNextVideo();
            }, maxDuration * 1000);
        }
    }

    if (event.data === YT_ENDED) {
        goToNextVideo();
    }
}

function goToNextVideo() {
    const videos = window.__TV_VIDEOS;
    currentIndex = (currentIndex + 1) % videos.length;
    loadVideo(currentIndex);
}
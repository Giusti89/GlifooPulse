let player = null;
let currentIndex = 0;
let currentProvider = null;
let tiktokIframe = null;

const videos = window.__TV_VIDEOS || [];


/**
 * Detecta la plataforma según la URL.
 */
function detectProvider(url) {

    if (!url) {
        return null;
    }

    try {

        const parsedUrl = new URL(url);

        const hostname = parsedUrl.hostname.toLowerCase();

        if (
            hostname.includes('youtube.com') ||
            hostname.includes('youtu.be')
        ) {
            return 'youtube';
        }

        if (hostname.includes('tiktok.com')) {
            return 'tiktok';
        }

    } catch (error) {

        console.error('URL inválida:', url);
    }

    return null;
}


/**
 * Extrae el ID de un video de YouTube.
 *
 * Soporta:
 * /watch?v=
 * /shorts/
 * /embed/
 * youtu.be/
 */
function extractYouTubeId(url) {

    try {

        const parsedUrl = new URL(url);

        const hostname = parsedUrl.hostname.toLowerCase();

        // youtu.be/VIDEO_ID
        if (hostname === 'youtu.be') {
            return parsedUrl.pathname.substring(1);
        }

        // youtube.com/...
        if (hostname.includes('youtube.com')) {

            // /watch?v=VIDEO_ID
            const videoParam = parsedUrl.searchParams.get('v');

            if (videoParam) {
                return videoParam;
            }

            // /shorts/VIDEO_ID
            const shortsMatch =
                parsedUrl.pathname.match(/\/shorts\/([^/]+)/);

            if (shortsMatch) {
                return shortsMatch[1];
            }

            // /embed/VIDEO_ID
            const embedMatch =
                parsedUrl.pathname.match(/\/embed\/([^/]+)/);

            if (embedMatch) {
                return embedMatch[1];
            }
        }

    } catch (error) {

        console.error('No se pudo analizar URL YouTube:', url);
    }

    return null;
}


/**
 * Extrae el ID de un video de TikTok.
 *
 * Ejemplo:
 * https://www.tiktok.com/@usuario/video/123456789
 */
function extractTikTokId(url) {

    try {

        const parsedUrl = new URL(url);

        const match =
            parsedUrl.pathname.match(/\/video\/(\d+)/);

        return match ? match[1] : null;

    } catch (error) {

        console.error('No se pudo analizar URL TikTok:', url);

        return null;
    }
}


/**
 * Inicializa el reproductor.
 */
function initPlayer() {

    if (!videos.length) {
        return;
    }

    console.log('🎬 Videos encontrados:', videos);

    loadVideo(currentIndex);
}


/**
 * YouTube API lista.
 */
function onYouTubeIframeAPIReady() {

    console.log('🎥 YouTube API lista');

    initPlayer();
}


/**
 * Carga un video según su plataforma.
 */
function loadVideo(index) {

    const url = videos[index];

    if (!url) {
        console.error('❌ No existe video para el índice:', index);
        return;
    }

    const provider = detectProvider(url);

    console.log('================================');
    console.log('🎬 Cargando video');
    console.log('Índice:', index);
    console.log('URL:', url);
    console.log('Proveedor:', provider);

    if (!provider) {

        console.error('❌ Plataforma no soportada:', url);

        goToNextVideo();

        return;
    }

    currentProvider = provider;

    // Limpiamos el reproductor anterior
    destroyCurrentPlayer();

    if (provider === 'youtube') {

        loadYouTubeVideo(url);

    } else if (provider === 'tiktok') {

        loadTikTokVideo(url);
    }
}


/**
 * Carga YouTube.
 */
function loadYouTubeVideo(url) {

    const videoId = extractYouTubeId(url);

    console.log('🎥 YouTube ID:', videoId);

    if (!videoId) {

        console.error('❌ No se pudo obtener ID de YouTube');

        goToNextVideo();

        return;
    }

    const container = document.getElementById('player');

    container.innerHTML = '';

    const playerElement = document.createElement('div');

    container.appendChild(playerElement);

    player = new YT.Player(playerElement, {

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

            onReady: function (event) {

                console.log('▶️ YouTube listo');

                event.target.playVideo();
            },

            onStateChange: onYouTubeStateChange,

            onError: function (event) {

                console.error(
                    '❌ Error YouTube:',
                    event.data
                );
            }
        }
    });
}


/**
 * Estados de YouTube.
 */
function onYouTubeStateChange(event) {

    console.log(
        'Estado YouTube:',
        event.data
    );

    if (event.data === YT.PlayerState.ENDED) {

        console.log('🔥 YouTube terminó');

        goToNextVideo();
    }
}


/**
 * Carga TikTok.
 */
function loadTikTokVideo(url) {

    const videoId = extractTikTokId(url);

    console.log('🎵 TikTok ID:', videoId);

    if (!videoId) {

        console.error('❌ No se pudo obtener ID de TikTok');

        goToNextVideo();

        return;
    }

    const container = document.getElementById('player');

    container.innerHTML = '';

    tiktokIframe = document.createElement('iframe');

    tiktokIframe.src =
        `https://www.tiktok.com/player/v1/${videoId}` +
        `?autoplay=1&muted=1&controls=1&loop=0`;

    tiktokIframe.width = '100%';
    tiktokIframe.height = '100%';

    tiktokIframe.frameBorder = '0';

    tiktokIframe.allow =
        'autoplay; fullscreen';

    tiktokIframe.setAttribute(
        'allowfullscreen',
        ''
    );

    tiktokIframe.title = 'TikTok video';

    container.appendChild(tiktokIframe);

    console.log('▶️ TikTok cargado');
}


/**
 * Escucha los mensajes enviados por TikTok.
 */
window.addEventListener('message', function (event) {

    if (
        event.origin !== 'https://www.tiktok.com'
    ) {
        return;
    }

    const data = event.data;

    if (!data || data['x-tiktok-player'] !== true) {
        return;
    }

    console.log(
        '📩 Evento TikTok:',
        data
    );

    if (data.type === 'onPlayerReady') {

        console.log('▶️ TikTok listo');

        tiktokIframe.contentWindow.postMessage(
            {
                type: 'play',
                'x-tiktok-player': true
            },
            'https://www.tiktok.com'
        );
    }

    if (data.type === 'onStateChange') {

        console.log(
            'Estado TikTok:',
            data.value
        );

        // TikTok:
        // 0 = ended
        // 1 = playing
        // 2 = paused
        // 3 = buffering

        if (data.value === 0) {

            console.log('🔥 TikTok terminó');

            goToNextVideo();
        }
    }

});


/**
 * Pasa al siguiente video.
 */
function goToNextVideo() {

    currentIndex =
        (currentIndex + 1) % videos.length;

    console.log(
        '➡️ Siguiente video:',
        currentIndex
    );

    loadVideo(currentIndex);
}


/**
 * Destruye el reproductor actual.
 */
function destroyCurrentPlayer() {

    // YouTube
    if (player) {

        try {
            player.destroy();
        } catch (error) {
            console.warn(
                'No se pudo destruir YouTube:',
                error
            );
        }

        player = null;
    }

    // TikTok
    tiktokIframe = null;

    const container =
        document.getElementById('player');

    if (container) {
        container.innerHTML = '';
    }
}
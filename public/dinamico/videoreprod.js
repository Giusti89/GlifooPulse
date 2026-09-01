let player = null;
let currentIndex = 0;
let currentProvider = null;
let tiktokIframe = null;

const videos = window.__TV_VIDEOS || [];
const volumeButton = document.getElementById('volume-toggle');

let isMuted = true;


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

        // YouTube
        if (
            hostname === 'youtube.com' ||
            hostname.endsWith('.youtube.com') ||
            hostname === 'youtu.be'
        ) {
            return 'youtube';
        }

        // TikTok
        if (
            hostname === 'tiktok.com' ||
            hostname.endsWith('.tiktok.com')
        ) {
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
 * - youtube.com/watch?v=ID
 * - youtu.be/ID
 * - youtube.com/shorts/ID
 * - youtube.com/embed/ID
 */
function extractYouTubeId(url) {

    try {

        const parsedUrl = new URL(url);
        const hostname = parsedUrl.hostname.toLowerCase();

        // youtu.be/VIDEO_ID
        if (hostname === 'youtu.be') {

            return parsedUrl.pathname
                .split('/')
                .filter(Boolean)[0] || null;
        }

        if (
            hostname === 'youtube.com' ||
            hostname.endsWith('.youtube.com')
        ) {

            // youtube.com/watch?v=VIDEO_ID
            const videoParam =
                parsedUrl.searchParams.get('v');

            if (videoParam) {
                return videoParam;
            }

            const pathParts =
                parsedUrl.pathname
                    .split('/')
                    .filter(Boolean);

            // youtube.com/shorts/VIDEO_ID
            if (pathParts[0] === 'shorts') {
                return pathParts[1] || null;
            }

            // youtube.com/embed/VIDEO_ID
            if (pathParts[0] === 'embed') {
                return pathParts[1] || null;
            }
        }

    } catch (error) {

        console.error(
            'No se pudo analizar URL YouTube:',
            url
        );
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

        const pathParts =
            parsedUrl.pathname
                .split('/')
                .filter(Boolean);

        const videoIndex =
            pathParts.indexOf('video');

        if (videoIndex !== -1) {

            return pathParts[videoIndex + 1] || null;
        }

    } catch (error) {

        console.error(
            'No se pudo analizar URL TikTok:',
            url
        );
    }

    return null;
}


/**
 * Inicializa el reproductor.
 */
function initPlayer() {

    if (!videos.length) {
        return;
    }

    loadVideo(currentIndex);
}


/**
 * YouTube API lista.
 */
function onYouTubeIframeAPIReady() {

    initPlayer();
}


/**
 * Carga un video según su plataforma.
 */
function loadVideo(index) {

    const url = videos[index];

    if (!url) {
        return;
    }

    const provider = detectProvider(url);

    if (!provider) {

        console.error(
            '❌ Plataforma no soportada:',
            url
        );

        goToNextVideo();

        return;
    }

    currentProvider = provider;

    // Cada video comienza silenciado.
    isMuted = true;

    updateVolumeButton();

    destroyCurrentPlayer();

    if (provider === 'youtube') {

        loadYouTubeVideo(url);

    } else if (provider === 'tiktok') {

        loadTikTokVideo(url);
    }
}


/**
 * Carga un video de YouTube.
 */
function loadYouTubeVideo(url) {

    const videoId = extractYouTubeId(url);

    if (!videoId) {

        console.error(
            '❌ No se pudo obtener ID de YouTube'
        );

        goToNextVideo();

        return;
    }

    const container =
        document.getElementById('player');

    if (!container) {
        return;
    }

    container.innerHTML = '';

    const playerElement =
        document.createElement('div');

    container.appendChild(playerElement);

    player = new YT.Player(
        playerElement,
        {

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

                    event.target.playVideo();
                },

                onStateChange:
                    onYouTubeStateChange,

                onError: function (event) {

                    console.error(
                        '❌ Error YouTube:',
                        event.data
                    );

                    goToNextVideo();
                }
            }
        }
    );
}


/**
 * Estados de YouTube.
 */
function onYouTubeStateChange(event) {

    if (
        event.data ===
        YT.PlayerState.ENDED
    ) {

        goToNextVideo();
    }
}


/**
 * Carga un video de TikTok.
 */
function loadTikTokVideo(url) {

    const videoId =
        extractTikTokId(url);

    if (!videoId) {

        console.error(
            '❌ No se pudo obtener ID de TikTok'
        );

        goToNextVideo();

        return;
    }

    const container =
        document.getElementById('player');

    if (!container) {
        return;
    }

    container.innerHTML = '';

    tiktokIframe =
        document.createElement('iframe');

    tiktokIframe.src =
        `https://www.tiktok.com/player/v1/${videoId}` +
        `?autoplay=1&controls=1&volume_control=0&loop=0`;

    tiktokIframe.width = '100%';
    tiktokIframe.height = '100%';

    tiktokIframe.frameBorder = '0';

    tiktokIframe.allow =
        'autoplay; fullscreen';

    tiktokIframe.title =
        'TikTok video';

    container.appendChild(
        tiktokIframe
    );
}


/**
 * Escucha los mensajes enviados por TikTok.
 */
window.addEventListener(
    'message',
    function (event) {

        if (
            event.origin !==
            'https://www.tiktok.com'
        ) {
            return;
        }

        const data = event.data;

        if (
            !data ||
            data['x-tiktok-player'] !== true
        ) {
            return;
        }


        // TikTok está listo.
        if (
            data.type ===
            'onPlayerReady'
        ) {
            return;
        }


        // Estado de mute.
        if (
            data.type ===
            'onMute'
        ) {

            isMuted = data.value;

            updateVolumeButton();

            return;
        }


        // Cambio de volumen.
        if (
            data.type ===
            'onVolumeChange'
        ) {
            return;
        }


        // Cambio de estado.
        if (
            data.type ===
            'onStateChange'
        ) {

            if (data.value === 0) {

                goToNextVideo();
            }

            return;
        }


        // Error del reproductor.
        if (
            data.type ===
            'onPlayerError'
        ) {

            console.error(
                '❌ TikTok Player Error:',
                data
            );

            goToNextVideo();
        }
    }
);


/**
 * Actualiza el botón de volumen.
 */
function updateVolumeButton() {

    if (!volumeButton) {
        return;
    }

    const icon =
        volumeButton.querySelector('i');

    if (!icon) {
        return;
    }

    if (isMuted) {

        icon.className =
            'fas fa-volume-mute';

        volumeButton.setAttribute(
            'aria-label',
            'Activar sonido'
        );

        volumeButton.setAttribute(
            'title',
            'Activar sonido'
        );

    } else {

        icon.className =
            'fas fa-volume-up';

        volumeButton.setAttribute(
            'aria-label',
            'Silenciar'
        );

        volumeButton.setAttribute(
            'title',
            'Silenciar'
        );
    }
}


/**
 * Activa o desactiva el sonido.
 */
function toggleVolume() {

    if (
        currentProvider ===
        'tiktok'
    ) {

        if (!tiktokIframe) {
            return;
        }

        const command =
            isMuted
                ? 'unMute'
                : 'mute';

        tiktokIframe
            .contentWindow
            .postMessage(
                {
                    type: command,
                    'x-tiktok-player': true
                },
                '*'
            );

        return;
    }


    if (
        currentProvider ===
        'youtube'
    ) {

        if (!player) {
            return;
        }

        if (isMuted) {

            player.unMute();
            player.setVolume(100);

        } else {

            player.mute();
        }
    }
}


/**
 * Pasa al siguiente video.
 */
function goToNextVideo() {

    if (!videos.length) {
        return;
    }

    currentIndex =
        (currentIndex + 1) %
        videos.length;

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


    // Limpiar contenedor
    const container =
        document.getElementById('player');

    if (container) {

        container.innerHTML = '';
    }
}


/**
 * Registrar el botón de volumen UNA SOLA VEZ.
 */
if (volumeButton) {

    volumeButton.addEventListener(
        'click',
        toggleVolume
    );
}
<div id="tv-player" class="video-wrapper">
    <div id="player"></div>
</div>

<script>
    window.__TV_VIDEOS = @json($videos->pluck('url_embed'));
</script>
<script src="https://www.youtube.com/iframe_api"></script>
<script src="{{ asset('dinamico/videoreprod.js') }}?v={{ filemtime(public_path('dinamico/videoreprod.js')) }}"></script>

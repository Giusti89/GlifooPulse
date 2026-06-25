{!! '<'.'?xml version="1.0" encoding="UTF-8"?'.'>' !!}

<urlset
xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    <url>
        <loc>{{ route('inicio') }}</loc>
    </url>

    @foreach($spots as $spot)
        <url>
            <loc>{{ route('publicidad', $spot->slug) }}</loc>
            <lastmod>{{ $spot->updated_at->toAtomString() }}</lastmod>
        </url>
    @endforeach

</urlset>
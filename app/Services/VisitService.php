<?php

namespace App\Services;

use App\Models\Spot;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VisitService
{
    public function register(Request $request, Spot $spot): void
    {
        $userAgent = strtolower($request->userAgent() ?? '');

        if ($this->isBot($userAgent)) {
            return;
        }

        if ($request->cookie('cookies_ok') !== '1') {
            return;
        }

        $sessionId = $this->resolveSessionId($request);
        if (! $sessionId) {
            return; 
        }

        $referrer = $request->headers->get('referer');

        Visit::create([
            'spot_id'         => $spot->id,
            'ip'              => md5($request->ip() ?? ''),
            'user_agent'      => Str::limit($userAgent, 255),
            'referrer'        => $referrer ? Str::limit($referrer, 500) : null,
            'referrer_domain' => $this->normalizeReferrer($referrer),
            'utm_source'      => $this->cleanUtm($request->query('utm_source')),
            'utm_medium'      => $this->cleanUtm($request->query('utm_medium')),
            'utm_campaign'    => $this->cleanUtm($request->query('utm_campaign')),
            'device_type'     => $this->detectDevice($userAgent),
            'session_id'      => $sessionId,
            'visited_at'      => now(),
        ]);

        $spot->increment('contador');

        $this->rememberOrigin($request);
    }

    protected function isBot(string $ua): bool
    {
        return Str::contains($ua, [
            'bot',
            'crawl',
            'spider',
            'slurp',
            'facebookexternalhit',
            'whatsapp',
            'telegrambot',
            'twitterbot',
            'linkedinbot',
            'preview',
        ]);
    }

    protected function resolveSessionId(Request $request): ?string
    {
        // Si el usuario ya trae cookie de sesión, es la misma sesión
        $existing = $request->input('_sid') ?? $request->cookie('_sid');
        if ($existing) {
            return $existing;
        }

        // Ventana anti-spam por IP (30 min)
        $cacheKey = 'visit_recent:' . md5($request->ip() ?? '');

        if (cache()->has($cacheKey)) {
            return null;
        }

        cache()->put($cacheKey, true, now()->addMinutes(30));

        $sid = Str::random(32);
        cookie()->queue(cookie('_sid', $sid, 60 * 24)); // 1 día

        return $sid;
    }

    protected function normalizeReferrer(?string $referrer): ?string
    {
        if (! $referrer) {
            return null;
        }

        $host = parse_url($referrer, PHP_URL_HOST);
        if (! $host) {
            return null;
        }

        // Quitar subdominios comunes de redes sociales
        $host = preg_replace('/^(www|l|m)\./', '', $host);

        // Si el referrer es tu propio dominio, no lo cuentes como externo
        $ownHost = parse_url(config('app.url'), PHP_URL_HOST);
        if ($ownHost && str_contains($host, $ownHost)) {
            return null;
        }

        return $host;
    }

    protected function detectDevice(string $ua): string
    {
        if (preg_match('/tablet|ipad/i', $ua)) {
            return 'tablet';
        }
        if (preg_match('/mobile|android|iphone|ipod/i', $ua)) {
            return 'mobile';
        }
        return 'desktop';
    }

    protected function cleanUtm(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        return Str::limit(
            preg_replace('/[^A-Za-z0-9_\-]/', '', $value),
            100,
            ''
        );
    }

    protected function rememberOrigin(Request $request): void
    {
        $utmSource   = $request->query('utm_source');
        $utmCampaign = $request->query('utm_campaign');

        if (! $utmSource && ! $utmCampaign) {
            return;
        }

        cookie()->queue(cookie(
            'origen_visita',
            json_encode([
                'source'   => $utmSource,
                'campaign' => $utmCampaign,
                'at'       => now()->toIso8601String(),
            ]),
            60 * 24 * 30 // 30 días
        ));
    }
}

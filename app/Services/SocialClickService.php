<?php

namespace App\Services;

use App\Models\Social;
use App\Models\SocialClicks;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SocialClickService
{
    public function registerClick(Social $social): void
    {
        $ip = request()->ip();
        $userAgent = request()->userAgent() ? strtolower(request()->userAgent()) : '';

        $bots = ['bot', 'crawl', 'spider', 'slurp', 'facebookexternalhit', 'whatsapp', 'telegrambot', 'twitterbot', 'linkedinbot'];

        if (Str::contains($userAgent, $bots)) {
            return;
        }

        $cacheKey = "click_limit:{$social->id}:" . md5($ip);

        if (Cache::has($cacheKey)) {
            return;
        }

        Cache::put($cacheKey, true, 10);

        $origin = $this->resolveOrigin();

        SocialClicks::create([
            'social_id'    => $social->id,
            'clicked_at'   => now(),
            'ip'           => md5($ip),
            'user_agent'   => Str::limit($userAgent, 255),
            'utm_source'   => $origin['source'] ?? null,
            'utm_campaign' => $origin['campaign'] ?? null,
            'device_type'  => $this->detectDevice($userAgent),
        ]);

        $social->increment('clicks');
    }

    protected function resolveOrigin(): array
    {
        $cookie = request()->cookie('origen_visita');
        if (! $cookie) {
            return [];
        }
        $data = json_decode($cookie, true);
        return is_array($data) ? $data : [];
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
}

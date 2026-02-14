<?php

namespace App\Services;

use App\Models\Social;
use App\Models\SocialClicks;
use Illuminate\Support\Str;

class SocialClickService
{
    public function registerClick(Social $social): void
    {
        $ip = request()->ip();
        $userAgent = request()->userAgent();

        // 1️⃣ Filtro básico de bots
        if ($userAgent && Str::contains(strtolower($userAgent), ['bot', 'crawl', 'spider'])) {
            return;
        }

        // 2️⃣ Evitar múltiples clicks en 10 segundos
        $recentClick = SocialClicks::where('social_id', $social->id)
            ->where('ip', $ip)
            ->where('clicked_at', '>=', now()->subSeconds(10))
            ->exists();

        if ($recentClick) {
            return;
        }

        // 3️⃣ Guardar evento
        SocialClicks::create([
            'social_id' => $social->id,
            'clicked_at' => now(),
            'ip' => $ip,
            'user_agent' => $userAgent,
        ]);

        // 4️⃣ Opcional: mantener contador general
        $social->increment('clicks');
    }
}

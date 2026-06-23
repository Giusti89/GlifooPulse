<?php

namespace App\Services;

use App\Models\Social;
use App\Models\SocialClicks;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class SocialClickService
{
    public function registerClick(Social $social): void
    {
        $ip = request()->ip();
        $userAgent = request()->userAgent() ? strtolower(request()->userAgent()) : '';

        // 1️⃣ Filtro ampliado de bots y previsualizaciones de redes sociales
        $bots = ['bot', 'crawl', 'spider', 'slurp', 'facebookexternalhit', 'whatsapp', 'telegrambot', 'twitterbot', 'linkedinbot'];

        if (Str::contains($userAgent, $bots)) {
            return;
        }

        // 2️⃣ Evitar múltiples clics usando Caché en lugar de consultas SQL (Mucho más rápido)
        $cacheKey = "click_limit:{$social->id}:" . md5($ip);

        if (Cache::has($cacheKey)) {
            return;
        }

        // Registramos el bloqueo en caché por 10 segundos
        Cache::put($cacheKey, true, 10);

        // 3️⃣ Guardar el evento histórico de forma asíncrona o directa
        SocialClicks::create([
            'social_id'  => $social->id,
            'clicked_at' => now(),
            'ip'         => md5($ip), // 🟢 Guardamos un hash por privacidad en lugar de la IP limpia
            'user_agent' => Str::limit($userAgent, 255),
        ]);
        // 4️⃣ Mantener contador general de forma segura
        $social->increment('clicks');
    }
}

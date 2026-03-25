<?php

namespace App\Http\Middleware;

use App\Models\Plataforma_visit;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPlatformVisit
{
    public function handle(Request $request, Closure $next)
    {
        // 🚫 evitar ruido (archivos estáticos)
        if (
            str_starts_with($request->path(), 'build') ||
            str_starts_with($request->path(), 'img') ||
            str_starts_with($request->path(), 'storage') ||
            str_starts_with($request->path(), 'fonts')
        ) {
            return $next($request);
        }

        // 🚫 evitar requests internos tipo livewire
        if ($request->is('livewire/*')) {
            return $next($request);
        }

        // 🚫 3. Filtrar bots básicos
        $userAgent = strtolower($request->userAgent() ?? '');

        if (
            str_contains($userAgent, 'bot') ||
            str_contains($userAgent, 'crawl') ||
            str_contains($userAgent, 'spider') ||
            str_contains($userAgent, 'slurp') ||
            str_contains($userAgent, 'curl') ||
            str_contains($userAgent, 'wget')
        ) {
            return $next($request);
        }

        // 🚫 4. Evitar duplicados (misma sesión en pocos segundos)
        $lastVisit = session('last_visit_time');

        if ($lastVisit && now()->diffInSeconds($lastVisit) < 10) {
            return $next($request);
        }

        session(['last_visit_time' => now()]);

        // ✅ 5. Guardar visita
        Plataforma_visit::create([
            'url' => $request->fullUrl(),
            'path' => $request->path(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referer' => $request->headers->get('referer'),
            'session_id' => $request->session()->getId(),
            'user_id' => auth()->id(),
        ]);

        return $next($request);
    }
}

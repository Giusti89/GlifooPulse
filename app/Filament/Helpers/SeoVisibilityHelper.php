<?php
namespace App\Filament\Helpers;


use Illuminate\Support\Facades\Cache;
use App\Models\Suscripcion;

class SeoVisibilityHelper
{
    public static function getUserSeoLevel()
    {
        static $seoLevel = null;
        
        if ($seoLevel === null) {
            $suscripcion = Suscripcion::with('paquete')
                ->where('user_id', auth()->id())
                ->where('estado', 1)
                ->first();
                
            $seoLevel = $suscripcion->paquete->seo_level ?? 'basico';
        }
        
        return $seoLevel;
    }
    
    public static function visibleForSeoLevel($minLevel)
    {
        $levels = ['basico' => 1, 'medio' => 2, 'completo' => 3];
        $userLevel = self::getUserSeoLevel();
        
        return $levels[$userLevel] >= $levels[$minLevel];
    }
}
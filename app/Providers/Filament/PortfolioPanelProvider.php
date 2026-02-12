<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Pages\Auth\Register;
use App\Http\Middleware\SuscripcionActiva;
use App\Http\Middleware\Checkfecha;
use App\Http\Middleware\RedirectToProperPanelMiddleware;
use Swindon\FilamentHashids\Middleware\FilamentHashidsMiddleware;

class PortfolioPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('portfolio')
            ->path('portfolio')
            ->passwordReset()
            ->profile()
            ->topNavigation()
            ->passwordReset()
            ->middleware([
                FilamentHashidsMiddleware::class,
            ])
            ->colors([
                'primary' => Color::Green,
            ])
            ->discoverResources(in: app_path('Filament/Portfolio/Resources'), for: 'App\\Filament\\Portfolio\\Resources')
            ->discoverPages(in: app_path('Filament/Portfolio/Pages'), for: 'App\\Filament\\Portfolio\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Portfolio/Widgets'), for: 'App\\Filament\\Portfolio\\Widgets')
            ->widgets([
                
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                SuscripcionActiva::class,
                Checkfecha::class,
                RedirectToProperPanelMiddleware::class,
            ]);
    }
}

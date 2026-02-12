<?php

namespace App\Providers\Filament;

use App\Http\Middleware\Checkfecha;
use App\Http\Middleware\RedirectToProperPanelMiddleware;
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
use App\Http\Middleware\EnsureSubscriptionIsValid;
use App\Http\Middleware\SuscripcionActiva;
use Swindon\FilamentHashids\Middleware\FilamentHashidsMiddleware;


class CatalogoPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('catalogo')
            ->path('catalogo')
            ->profile()
            ->passwordReset()
            ->topNavigation()
            ->middleware([
                FilamentHashidsMiddleware::class,
            ])
            ->colors([
                'primary' => Color::Orange,
            ])
            ->discoverResources(in: app_path('Filament/Catalogo/Resources'), for: 'App\\Filament\\Catalogo\\Resources')
            ->discoverPages(in: app_path('Filament/Catalogo/Pages'), for: 'App\\Filament\\Catalogo\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Catalogo/Widgets'), for: 'App\\Filament\\Catalogo\\Widgets')
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
                Authenticate::class,
                Checkfecha::class,
                EnsureSubscriptionIsValid::class,
            ]);
    }
}

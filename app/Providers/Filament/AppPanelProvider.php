<?php

namespace App\Providers\Filament;

use Andreia\FilamentUiSwitcher\FilamentUiSwitcherPlugin;
use App\Filament\Widgets\ActividadesComplementariasChart;
use App\Filament\Widgets\AsignaturasTableWidget;
use App\Filament\Widgets\HorasDocenciaChart;
use App\Filament\Widgets\InformesRecientesWidget;
use App\Filament\Widgets\WelcomeWidget;
use Caresome\FilamentAuthDesigner\AuthDesignerPlugin;
use Caresome\FilamentAuthDesigner\Enums\AuthLayout;
use Caresome\FilamentAuthDesigner\Enums\MediaDirection;
use Caresome\FilamentAuthDesigner\Enums\MediaPosition;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Filament\Widgets\StatsOverviewWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use pxlrbt\FilamentSpotlight\SpotlightPlugin;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->globalSearch(false)
            ->authGuard('web')
            ->navigationGroups([
                'Gestión Académica',
                'Académico',
                'Seguimiento',
            ])
            ->id('app')
            ->path('app')
            ->login()
            ->registration()
            ->passwordReset()
            ->emailVerification()
            ->brandLogoHeight('72px')
            ->favicon(asset('assets/favicon.ico'))
            ->brandLogo(fn () => view('filament.brand-logo'))
            ->viteTheme('resources/css/filament/app/theme.css')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                WelcomeWidget::class,
                StatsOverviewWidget::class,
                HorasDocenciaChart::class,
                ActividadesComplementariasChart::class,
                AsignaturasTableWidget::class,
                InformesRecientesWidget::class,
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
            ])
            ->favicon(asset('assets/logo-dark.png'))
            ->errorNotifications()
            ->databaseNotifications() // ← Esta línea es clave
            ->databaseNotificationsPolling('30s') // Opcional: polling cada 30s
            ->plugins([
                FilamentUiSwitcherPlugin::make()
                    ->withModeSwitcher(true),
                SpotlightPlugin::make(),
                ]
            );
    }
}

<?php

namespace App\Providers\Filament;

use App\Http\Middleware\IsAdmin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class NomadPanelProvider extends PanelProvider
{

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->sidebarCollapsibleOnDesktop()
            ->domain('admin.localhost')
            ->brandName('Nomad - Scriptum')
            ->spa(hasPrefetching: true)
            //->unsavedChangesAlerts() //NOTE: asks on 'composer run dev' reload, even if nothing was changed (even in table view)
            ->databaseTransactions()
            ->strictAuthorization()
            ->login()
            ->revealablePasswords(false)
            ->profile(isSimple: false)
            ->emailChangeVerification()
            ->id('nomad')
            ->path('')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->viteTheme([
                'resources/css/filament/nomad/theme.css',
                'resources/css/highlight/light-plus.css',
                'resources/css/highlight/dark-plus.css',
            ])

            // without the nomad panel
            //->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            //->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')

            ->discoverResources(in: app_path('Filament/Nomad/Resources'), for: 'App\Filament\Nomad\Resources')
            ->discoverPages(in: app_path('Filament/Nomad/Pages'), for: 'App\Filament\Nomad\Pages')
            ->pages([
                Dashboard::class,
            ])

            // without the nomad panel
            //->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')

            ->discoverWidgets(in: app_path('Filament/Nomad/Widgets'), for: 'App\Filament\Nomad\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                IsAdmin::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}

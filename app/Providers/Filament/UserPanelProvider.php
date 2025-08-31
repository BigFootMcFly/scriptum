<?php

namespace App\Providers\Filament;

use App\Filament\User\Pages\Auth\UserRegister;
use App\Filament\User\Resources\Notes\NoteResource;
use App\Livewire\FrontPage\TopBar;
use App\Utils\SmartSearch;
use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class UserPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->userMenuItems([
                'logout' => fn (Action $action) => $action->label('Log out'),
            ])
            /*->spaUrlExceptions(fn (): array => [
                route('user-logout'),
            ])*/

            //->maxContentWidth(Width::Full)
            ->globalSearch(false) //NOTE: we use our own
            //->globalSearchKeyBindings(['command+f', 'shift+ctrl+f'])
            ->topbarLivewireComponent(TopBar::class)
            ->sidebarCollapsibleOnDesktop(false)

            ->default()
            ->id('user')
            ->path('user')
            ->login()
            ->registration(UserRegister::class) // NOTE: our custom register page
            ->profile(isSimple: false)
            ->spa(hasPrefetching: true)
            ->topNavigation()
            ->emailChangeVerification()
            ->unsavedChangesAlerts()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->viteTheme([
                'resources/css/filament/user/theme.css',
                'resources/css/app.css',
            ])
            ->discoverResources(in: app_path('Filament/User/Resources'), for: 'App\Filament\User\Resources')
            ->discoverPages(in: app_path('Filament/User/Pages'), for: 'App\Filament\User\Pages')
            ->pages([
                //Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/User/Widgets'), for: 'App\Filament\User\Widgets')
            ->widgets([
                //NOTE: this are widgets on the dashboard page
                //AccountWidget::class,
                //FilamentInfoWidget::class,
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
                //Authenticate::class,
            ])
            ;
    }
}

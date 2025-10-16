<?php

namespace App\Providers\Filament;

use App\Filament\User\Pages\Auth\UserRegister;
use App\Livewire\FrontPage\TopBar;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Auth\MultiFactor\Email\EmailAuthentication;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;

class UserPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->brandLogo(fn () => view('livewire.front-page.brand-logo'))
            ->globalSearch(false) //NOTE: we use our own
            ->topbarLivewireComponent(TopBar::class)
            ->sidebarCollapsibleOnDesktop(false)
            ->domain(config('scriptum.production.domain'))
            ->id('user')
            ->path('')
            ->registration(UserRegister::class) // NOTE: our custom register page
            ->profile(isSimple: false)
            ->multiFactorAuthentication([
                EmailAuthentication::make(),
                AppAuthentication::make()
                    ->recoverable()
                    ->regenerableRecoveryCodes(false),
            ])
            ->topNavigation()
            ->emailChangeVerification()
            ->unsavedChangesAlerts()
            ->renderHook(
                PanelsRenderHook::FOOTER,
                fn () => view('components.note.page-footer')
            )
            ->colors([
                'primary' => Color::Amber,
            ])
            ->viteTheme([
                'resources/css/filament/user/theme.css',
                'resources/css/app.css',
            ])
            ->discoverResources(in: app_path('Filament/User/Resources'), for: 'App\Filament\User\Resources')
            ->discoverPages(in: app_path('Filament/User/Pages'), for: 'App\Filament\User\Pages')
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
            ->plugins([
                FilamentEditProfilePlugin::make()
                    ->canAccess(fn () => auth()->check())
                    ->shouldRegisterNavigation(false)
                    ->shouldShowAvatarForm()
                    ->shouldShowBrowserSessionsForm()
                    ->shouldShowDeleteAccountForm(false)
                    ->shouldShowEmailForm(false)
                    ->shouldShowMultiFactorAuthentication(true)
            ]);
 /*
            //NOTE: moved to TopBar->mount()
            ->userMenuItems([
                'logout' => fn (Action $action) => $action->label('Log out'),
                'profile' => fn (Action $action) => $action->url(fn (): string => '/user/edit-profile'),
            ]);
*/
    }
}

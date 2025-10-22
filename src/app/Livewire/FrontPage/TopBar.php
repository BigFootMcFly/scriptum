<?php

namespace App\Livewire\FrontPage;

use Filament\Actions\Action;
use Filament\Livewire\Concerns\HasTenantMenu;
use Filament\Panel\Concerns\HasUserMenu;
use Filament\Support\Concerns\EvaluatesClosures;
use Livewire\Attributes\On;
use Livewire\Component;

class TopBar extends Component
{
    use EvaluatesClosures;
    use HasTenantMenu;
    use HasUserMenu;

    #[On('refresh-topbar')]
    public function refresh(): void {}

    public function mount()
    {

        $this->userMenuItems([
            Action::make('profile')
                ->label(__('Profile'))
                ->icon('heroicon-o-user-circle')
                ->url(route('filament.user.pages.edit-profile'))
                ->sort(-1)
                ->defaultView(Action::GROUPED_VIEW),
            Action::make('login')
                ->label(__('Login / Register'))
                ->icon('heroicon-o-user-circle')
                ->url(route('filament.user.auth.login'))
                ->sort(1)
                ->defaultView(Action::GROUPED_VIEW)
                ->visible( fn (): bool => ! auth()->check()),
            // NOTE: this will take precedence over the default 'logout' action defined by filament
            Action::make('logout')
                ->label(__('Logout'))
                ->icon('heroicon-o-arrow-left-on-rectangle')
                ->url(route('logout-user'))
                ->postToUrl()
                ->sort(PHP_INT_MAX)
                ->defaultView(Action::GROUPED_VIEW),
        ]);

    }
}

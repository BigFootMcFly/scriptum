<?php

namespace App\Livewire\FrontPage;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Livewire\Concerns\HasTenantMenu;
use Filament\Panel\Concerns\HasUserMenu;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Concerns\EvaluatesClosures;
use Livewire\Attributes\On;
use Livewire\Component;

class TopBar extends Component implements HasActions, HasSchemas
{
    use EvaluatesClosures;
    use HasTenantMenu;
    use HasUserMenu;
    use InteractsWithActions;
    use InteractsWithSchemas;

    #[On('refresh-topbar')]
    public function refresh(): void {}


    public function mount() {
        $this->userMenuItems([
            Action::make('login')
                ->label(__('Login / Register'))
                ->icon('heroicon-o-user-circle')
                ->url(route('filament.user.auth.login'))
                ->sort(1)
                ->visible( fn (): bool => !auth()->check())
                ,
            //NOTE: this will tak precedence over the default 'logouz' action defined by filament
            Action::make('logout')
                ->label(__('Logout'))
                ->icon('heroicon-o-arrow-left-on-rectangle')
                ->url(route('logout-user'))
                ->postToUrl()
                ->sort(PHP_INT_MAX)
        ]);
    }


    public function render()
    {
        return view('livewire.front-page.top-bar');
    }
}

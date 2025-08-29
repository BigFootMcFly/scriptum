<?php

namespace App\Livewire\FrontPage;

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


    public function render()
    {
        return view('livewire.front-page.top-bar');
    }
}

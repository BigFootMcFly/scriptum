<?php

namespace App\Livewire\FrontPage;

use App\Filament\User\Pages\FrontPage;
use App\Filament\User\Resources\Notes\Pages\ListNotes;
use Livewire\Attributes\On;
use Livewire\Component;

class NewNoteButton extends Component
{

    #[On('spa-navigation')]
    public function onSpaNavigation(string $pathName): void {
        // add changees here if needed
    }

    public function render()
    {
        return view('livewire.front-page.new-note-button');
    }
}

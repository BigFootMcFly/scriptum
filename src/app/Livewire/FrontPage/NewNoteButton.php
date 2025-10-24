<?php

namespace App\Livewire\FrontPage;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class NewNoteButton extends Component
{
    #[On('spa-navigation')]
    public function onSpaNavigation(string $pathName): void
    {
        // add changees here if needed
    }

    public function render(): View
    {
        return view('livewire.front-page.new-note-button');
    }
}

<?php

namespace App\Livewire\FrontPage;

use Livewire\Attributes\On;
use Livewire\Component;

class NewNoteButton extends Component
{
    #[On('spa-navigation')]
    public function onSpaNavigation(string $pathName): void
    {
        // add changees here if needed
    }

    public function render()
    {
        return view('livewire.front-page.new-note-button');
    }
}

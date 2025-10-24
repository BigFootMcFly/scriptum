<?php

namespace App\Livewire\FrontPage;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class BackToTopButton extends Component
{
    public function render(): View
    {
        return view('livewire.front-page.back-to-top-button');
    }
}

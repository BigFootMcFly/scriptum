<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class PageFooter extends Component
{
    public function render(): View
    {
        return view('livewire.page-footer');
    }
}

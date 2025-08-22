<?php

namespace App\Livewire\MainPage;

use App\Models\Note as ModelsNote;
use Livewire\Component;

class Note extends Component
{

    public ModelsNote $note;

    public function render()
    {
        return view('livewire.main-page.note');
    }
}

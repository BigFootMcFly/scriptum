<?php

namespace App\Livewire\FrontPage;

use App\Models\Note as ModelsNote;
use Livewire\Component;

class Note extends Component
{

    public ModelsNote $note;

    public function render()
    {
        return view('livewire.front-page.note');
    }
}

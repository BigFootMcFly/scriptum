<?php

namespace App\Livewire\ViewNote;

use App\Models\Note as ModelsNote;
use Livewire\Attributes\On;
use Livewire\Component;

class Note extends Component
{

    public ModelsNote $note;

    public bool $pulse = false;


    #[On('refresh-note')]
    public function refreshNode(int $noteId): void
    {
        if ($noteId === $this->note->id) {
            $this->pulse = true;
            $this->note->refresh();
            $this->js('setTimeout(() => $el.classList.remove("animate-note-updated"), 2500)');
        }
    }

}

<?php

namespace App\Livewire;

use App\Models\Note;
use Livewire\Component;

class MainPage extends Component
{

    public string $search = '';

    public function updateSearch(): void
    {
        $this->updateNoteList();
    }

    protected function updateNoteList(): void
    {

    }

    protected function queryNodeList()
    {
        return Note::visibleTo()->paginate(10);
    }

    public function render()
    {
        return view('livewire.main-page')
            ->with('notes', $this->queryNodeList())
        ;
    }
}

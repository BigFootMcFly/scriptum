<?php

namespace App\Livewire;

use App\Models\Note;
use Livewire\Component;

class MainPage extends Component
{
/*
    protected static string $routeName = 'front-page';

    public static function routeName(): string
    {
        return static::$routeName;
    }
*/
    public string $search = '';

    public bool $partial = false;

    public function updateSearch(): void
    {
        $this->updateNoteList();
    }

    protected function updateNoteList(): void
    {

    }

    protected function queryNodeList()
    {
        $builder = Note::frontPage(auth()->user());
        if ($this->search !== '') {
            $builder->search($this->search, $this->partial);
        } else {
            $builder->orderBy('updated_at', 'desc');
        }
        return $builder->paginate(10);
    }

    public function render()
    {
        return view('livewire.main-page')
            ->with('notes', $this->queryNodeList())
        ;
    }
}

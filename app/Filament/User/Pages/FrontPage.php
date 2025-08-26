<?php

namespace App\Filament\User\Pages;

use App\Models\Note;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Livewire\WithPagination;

class FrontPage extends Page implements HasForms
{
    use InteractsWithForms;
    use WithPagination;


    //protected static ?string $title = 'Custom Page Title';

    //protected static ?string $navigationLabel = 'Main page';


    //protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';
    protected static ?string $title = 'My Notes';


    protected static ?string $slug = 'main-page';

    protected string $view = 'filament.user.pages.front-page';

    public string $search = '';

/* BEGIN */
public ?Note $editingNote = null;

public function openEditModal(Note $note): void
{
    $this->editingNote = $note;
    //$this->form->fill($note->toArray());
    $this->dispatch('open-modal', id: 'edit-note');
}

public function save(): void
{
    $this->validate();
    $this->editingNote->update($this->form->getState());
    $this->dispatch('close-modal', id: 'edit-note');
}

protected function getFormSchema(): array
{
    return [
        TextInput::make('title')->required(),
        Textarea::make('content')->rows(6),
    ];
}

/* END */



    public function notes()
    {
        return $this->queryNodeList();
        /*return Note::query()
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(5);*/
    }


    //TODO: make this dinamic based on search
    public static function getNavigationLabel(): string
    {
        return __('Main');
    }

    public function getHeader(): ?View
    {
        return view('filament.user.pages.front-page-header');
    }

    //TODO: make this dinamic based on search
    public function getTitle(): string|Htmlable
    {
        return 'Scriptum';
    }

    /*public function getHeading(): string
    {
        return __('Welcome to the world of quick notes...');
    }*/

    /*public function getSubheading(): ?string
    {
        return __('Custom Page Subheading');
    }*/


    protected function queryNodeList()
    {
        $builder = Note::frontPage(auth()->user());
        if ($this->search !== '') {
            $builder->search($this->search, $this->partial);
        }
        return $builder->paginate(10);
    }

/*
    public function render(): View
    {
        return view('filament.user.pages.front-page')
            ->with('notes', $this->queryNodeList())
        ;
    }*/

}

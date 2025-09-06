<?php

namespace App\Filament\User\Pages;
use App\Models\Note;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class FrontPage extends Page
{
    use WithPagination;

    //protected static ?string $title = 'Custom Page Title';

    //protected static ?string $navigationLabel = 'Main page';

    //protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';
    protected static ?string $title = 'My Notes';

    protected static ?string $slug = 'front-page';

    protected string $view = 'filament.user.pages.front-page';

    public bool $partial = true;
    public string $search = '';

    public ?Note $editingNote = null;

    // ----------------------------------------------------------------------------------------------------------------
    //TODO: make this dinamic based on search
    public static function getNavigationLabel(): string
    {
        return __('Main');
    }

    // ----------------------------------------------------------------------------------------------------------------
    public function getHeader(): ?View
    {
        return view('filament.user.pages.front-page-header');
    }

    //TODO: make this dinamic based on search
    // ----------------------------------------------------------------------------------------------------------------
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


    // Internal functions

    // ----------------------------------------------------------------------------------------------------------------
    #[On('search-updated')]
    public function onSearchUpdated(string $search): void {
        $this->search = $search;
        $this->refresh();
    }

    #[On('refresh-note-list')]
    public function refreshNoteList(): void {
        $this->refresh();
    }

    // ----------------------------------------------------------------------------------------------------------------
    public function mount() {
        $this->search = session('front-page-search', '');
    }

/*
    public function rendered()
    {
       $this->dispatch('scroll-to-top');
    }
*/

    // ----------------------------------------------------------------------------------------------------------------
    // DataBase helpers

    // ----------------------------------------------------------------------------------------------------------------
    public function notes()
    {
        return $this->queryNodeList();
    }

    // ----------------------------------------------------------------------------------------------------------------
    protected function queryNodeList()
    {
        $builder = Note::frontPage(auth()->user());

        //NOTE: search results are ordered by FTS RANK
        if ('' !== $this->search) {
            $builder->search($this->search, $this->partial);
        } else {
            $builder->orderBy('updated_at', 'desc');
        }
        $builder = $builder->paginate(perPage: 10);

        return $builder;
    }

}

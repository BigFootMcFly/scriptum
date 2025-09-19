<?php

namespace App\Filament\User\Pages;

use App\Enums\NoteVisibility;
use App\Filament\Traits\ModalNoteEditor;
use App\Filament\User\Resources\Notes\Schemas\NoteForm;
use App\Models\Note;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class FrontPage extends Page
{
    use WithPagination;
    use ModalNoteEditor;

    //protected static ?string $title = 'Custom Page Title';

    //protected static ?string $navigationLabel = 'Main page';

    //protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';
    protected static ?string $title = 'My Notes';

    //protected static ?string $slug = 'front-page';
    protected static ?string $slug = '/';

    protected string $view = 'filament.user.pages.front-page';

    //TODO: this is not currelnty used, maybe it should be removed
    public bool $partial = true;

    public string $search = '';

    protected bool $resetPagination = false;

    // ----------------------------------------------------------------------------------------------------------------
    //TODO: make this dynamic based on search
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

    // ----------------------------------------------------------------------------------------------------------------
    #[On('refresh-note-list')]
    public function refreshNoteList(): void {
        $this->resetPagination = true;
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
    public function getNotesProperty()
    {
        $query = $this->getQuery();

        $this->dispatch('front-page-updated');

        // reste the pagination to the first page
        $page = $this->resetPagination ? 1 : null and $this->resetPagination = false;
        /* //NOTE: the above is the same as this: ( i just keep this here for now, it is not a good practice ;) )
        $page = $this->resetPagination ? 1 : null;
        $this->resetPagination = false;
        */

        return $query->paginate(perPage: 10, page: $page);
    }

    // ----------------------------------------------------------------------------------------------------------------
    protected function getQuery()
    {
        $builder = Note::query()->frontPage(auth()->user());

        if ('' !== $this->search) {
            //NOTE: search results are ordered by FTS RANK
            $builder->search($this->search, $this->partial)->ranked();
        } else {
            $builder->orderBy('updated_at', 'desc');
        }

        return $builder->with('user');
    }
























}

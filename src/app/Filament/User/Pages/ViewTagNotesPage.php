<?php

namespace App\Filament\User\Pages;

use App\Filament\Traits\ModalNoteEditor;
use App\Models\Note;
use App\Models\User;
use Filament\Pages\Page;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Spatie\Tags\Tag;

class ViewTagNotesPage extends Page
{
    use ModalNoteEditor;
    use WithPagination;

    protected string $view = 'filament.user.pages.view-tag-notes-page';

    protected static bool $shouldRegisterNavigation = false;

    protected bool $resetPagination = false;

    public Tag $tag;

    // ----------------------------------------------------------------------------------------------------------------
    public function mount(string $name): void
    {
        $this->tag = Tag::findFromString($name);

        FilamentView::registerRenderHook(
            PanelsRenderHook::FOOTER,
            fn (): View => view('components.note.page-footer')
        );
    }

    // ----------------------------------------------------------------------------------------------------------------
    #[On('refresh-note-list')]
    public function refreshNoteList(): void
    {
        $this->resetPagination = true;
        $this->refresh();
    }

    // ----------------------------------------------------------------------------------------------------------------
    protected function queryNotes()
    {
        $list = Note::withAnyTags([$this->tag]);

        $currentUser = auth()->user() ?? User::guestUser();

        return $list->frontPage(user: $currentUser);

    }

    // ----------------------------------------------------------------------------------------------------------------
    public function getNotesProperty()
    {
        $query = $this->queryNotes();

        $this->dispatch('user-notes-page-updated');

        // reset the pagination to the first page
        $page = $this->resetPagination ? 1 : null;
        $this->resetPagination = false;

        return $query->paginate(perPage: 10, page: $page);
    }

    public function getHeader(): ?View
    {
        return view('filament.user.pages.view-tag-notes.header')
            ->with('tag', $this->tag)
            ->with('notes', $this->queryNotes());
    }
}

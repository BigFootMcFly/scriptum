<?php

namespace App\Filament\User\Pages;

use App\Filament\Traits\ModalNoteEditor;
use App\Models\User;
use Filament\Pages\Page;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class ViewUserNotesPage extends Page
{
    use ModalNoteEditor;
    use WithPagination;

    protected string $view = 'filament.user.pages.view-user-notes-page';

    protected static bool $shouldRegisterNavigation = false;

    protected bool $resetPagination = false;

    public User $user;

    // ----------------------------------------------------------------------------------------------------------------
    public function mount(string $user): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::FOOTER,
            fn (): View => view('components.note.page-footer')
        );
    }

    // ----------------------------------------------------------------------------------------------------------------
    #[On('refresh-user-note-list')]
    public function refreshNoteList(): void
    {
        $this->resetPagination = true;
        $this->refresh();
    }

    // ----------------------------------------------------------------------------------------------------------------
    protected function queryNotes()
    {
        $list = $this->user->notes();
        $currentUser = auth()->user() ?? User::guestUser();

        if ($currentUser->is($this->user)) {
            return $list;
        }

        return $list->public();
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

    public function getHeading(): string
    {
        return __("Notes of \"{$this->user->name}\"");
    }

    public function getHeader(): ?View
    {
        return view('filament.user.pages.view-user-notes.header')
            ->with('user', $this->user);
    }
    /*
    public function getSubHeading(): string
    {
        return __("Notes of \"{$this->user->name}\"");
    }
*/
}

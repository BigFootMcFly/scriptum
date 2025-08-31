<?php

namespace App\Filament\User\Pages;

use App\Enums\NoteVisibility;
use App\Filament\User\Resources\Notes\Schemas\NoteForm;
use App\Models\Note;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
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

    public bool $partial = true;

    public ?array $data = [
        'title' => null,
        'visibility'  => null,
        'slug' => null,
        'body' => [],
    ];

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
    #[On('refresh-note-list')]
    public function refreshNoteList(string $search): void {
        $this->search = $search;
        $this->refresh();
    }

    // ----------------------------------------------------------------------------------------------------------------
    public function mount() {
        $this->search = session('front-page-search', '');
    }

    // Note editing/creating modal functions

    // ----------------------------------------------------------------------------------------------------------------
    public function form(Schema $schema): Schema
    {
        return NoteForm::configure($schema)
            ->statePath('data');
    }

    // ----------------------------------------------------------------------------------------------------------------
    #[On('create-new-note')]
    public function openEditModal(?Note $note = null): void
    {
        //NOTE: a new Note object is injected if none is provided by the client
        if (null === $note->id) { // create new note
            $this->editingNote = null;
            $this->data = [
                'body' => [],
                'visibility' => NoteVisibility::Private,
                'title' => '',
                'slug' => '',
            ];
        } else { // update an existing note
            $this->editingNote = $note;
            $this->form->fill($note->toArray());
        }

        $this->dispatch('open-modal', id: 'edit-note');
    }

    // ----------------------------------------------------------------------------------------------------------------
    public function cancelEditForm(): void
    {
        $message = match ($this->editingNote) {
            null => 'Creating note cancelled',
            default => 'Editing note cancelled',
        };
        $this->editingNote = null;
        $this->dispatch('close-modal', id: 'edit-note');
        Notification::make()
            ->title($message)
            ->info()
            ->send();
    }

    // ----------------------------------------------------------------------------------------------------------------
   public function saveNote(): void
    {
        $confirmMessage = 'New note created';

        $this->validate();

        if ($this->editingNote) { // update note
            $this->editingNote->update($this->form->getState());
            $confirmMessage = 'Note updated';
        } else { // create note
            Note::create(
                $this->data
                + ['user_id' => auth()->user()->id]
            );
        }

        $this->editingNote = null;

        $this->dispatch('close-modal', id: 'edit-note');
        Notification::make()
            ->title($confirmMessage)
            ->success()
            ->send();
    }

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
            $builder->orderBy('created_at', 'desc');
        }
        return $builder->paginate(10);
    }

}

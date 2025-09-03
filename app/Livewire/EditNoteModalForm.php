<?php

namespace App\Livewire;

use App\Enums\NoteVisibility;
use App\Filament\User\Resources\Notes\Schemas\NoteForm;
use App\Models\Note;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Livewire\Attributes\On;
use Livewire\Component;

class EditNoteModalForm extends Component implements HasForms
{
    use InteractsWithForms;

    public ?Note $note = null;

    public ?Note $editingNote = null; // ??? kell ez ???

    public ?array $data = [
        'title' => null,
        'visibility'  => null,
        'slug' => null,
        'body' => [],
    ];

    // ----------------------------------------------------------------------------------------------------------------
    public function form(Schema $schema): Schema
    {
        return NoteForm::configure($schema)
            ->statePath('data');
    }

    #[On('create-new-note')]
    #[On('edit-note')]
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
            $this->dispatch('refresh-note-list');
        }

        $this->editingNote = null;

        $this->dispatch('close-modal', id: 'edit-note');
        Notification::make()
            ->title($confirmMessage)
            ->success()
            ->send();
    }

/*
    public function render()
    {
        return view('livewire.edit-note-modal-form');
    }
*/
}

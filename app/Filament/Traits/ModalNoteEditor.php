<?php

namespace App\Filament\Traits;

use App\Enums\NoteVisibility;
use App\Filament\User\Resources\Notes\Schemas\NoteForm;
use App\Models\Note;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Illuminate\View\View;
use Livewire\Attributes\On;

trait ModalNoteEditor
{
    public ?array $data = [
        'title' => null,
        'visibility'  => null,
        'slug' => null,
        'body' => [
            "type" => "doc",
        ],
    ];

    public function getModalView(): View
    {
        return view('filament.traits.modal-note-editor');
    }

    public ?Note $editingNote = null;


    // ----------------------------------------------------------------------------------------------------------------
    public function form(Schema $schema): Schema
    {
        return NoteForm::configure($schema)
            ->statePath('data');
    }


    // ----------------------------------------------------------------------------------------------------------------
    #[On('create-new-note')]
    #[On('edit-note')]
    public function openEditModal(?Note $note = null): void
    {
        //NOTE: a new Note object is injected if none is provided by the client
        if (null === $note->id) { // create new note
            $this->editingNote = null;
            $this->data = [
                /*'body' => [],*/
                'body' => [
                    "type" => "doc",
                ],

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
            $noteUpdates = $this->editingNote;
            $confirmMessage = 'Note updated';
            $this->dispatch('refresh-note', noteId: $noteUpdates->id);
        } else { // create note
            $noteUpdates = Note::create(
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

}

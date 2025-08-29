<?php

namespace App\Filament\User\Resources\Notes\Pages;

use App\Filament\User\Resources\Notes\NoteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\On;

class ListNotes extends ListRecords
{
    protected static string $resource = NoteResource::class;

    #[On('refresh-note-list')]
    public function refreshPosts(): void
    {
        $this->resetPage();
        //$this->dispatch('$refresh'); // Refreshes the Livewire component
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\User\Resources\Notes\Pages;

use App\Filament\Traits\ModalNoteEditor;
use App\Filament\User\Resources\Notes\NoteResource;
use App\Models\Note;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;

class ListNotes extends ListRecords
{
    use ModalNoteEditor;

    public function mount(): void
    {
        if (! auth()->check()) {
            abort(403, 'Please login to manage your notes.');
        }
    }

    protected static string $resource = NoteResource::class;

    #[On('refresh-note-list')]
    public function refreshPosts(): void
    {
        $this->resetPage();
        // $this->dispatch('$refresh'); // Refreshes the Livewire component
    }

    #[On('search-updated')]
    public function onSearchUpdated(string $search): void
    {
        $this->resetPage();
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
                ->badge(auth()->user()->notes()->sessionSearch()->withTrashed()->count())
                ->badgeColor('info'),
            'public' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query): Builder => Note::builder($query)->publicOnly())
                ->badge(auth()->user()->notes()->sessionSearch()->withTrashed()->publicOnly()->count()),
            'private' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query): Builder => Note::builder($query)->privateOnly())
                ->badge(auth()->user()->notes()->sessionSearch()->withTrashed()->privateOnly()->count())
                ->badgeColor('success'),
            'deleted' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query): Builder => Note::builder($query)->onlyTrashed())
                ->badge(auth()->user()->notes()->sessionSearch()->onlyTrashed()->count())
                ->badgeColor('danger'),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            // NOTE: moved functionality to the main "Ad New Note" button
            // CreateAction::make(),
        ];
    }
}

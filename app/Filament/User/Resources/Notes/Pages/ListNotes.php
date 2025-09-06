<?php

namespace App\Filament\User\Resources\Notes\Pages;

use App\Filament\User\Resources\Notes\NoteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
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

    #[On('search-updated')]
    public function onSearchUpdated(string $search): void
    {
        $this->resetPage();
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
                ->badge(auth()->user()->notes()->withTrashed()->count())
                ->badgeColor('info'),
            'public' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->public())
                ->badge(auth()->user()->notes()->withTrashed()->public()->count()),
            'private' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->private())
                ->badge(auth()->user()->notes()->withTrashed()->private()->count())
                ->badgeColor('success'),
            'deleted' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->onlyTrashed())
                ->badge(auth()->user()->notes()->onlyTrashed()->count())
                ->badgeColor('danger'),
        ];
    }


    protected function getHeaderActions(): array
    {
        return [
            //NOTE: moved functionality to the main "Ad New Note" button
            //CreateAction::make(),
        ];
    }
}

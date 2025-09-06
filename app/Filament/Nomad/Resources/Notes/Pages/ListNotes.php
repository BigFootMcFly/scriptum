<?php

namespace App\Filament\Nomad\Resources\Notes\Pages;

use App\Enums\NoteVisibility;
use App\Filament\Nomad\Resources\Notes\NoteResource;
use App\Models\Note;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListNotes extends ListRecords
{
    protected static string $resource = NoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
                ->badge(Note::withTrashed()->count())
                ->badgeColor('info'),
            'deleted' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('deleted_at', '<>', null))
                ->badge(Note::onlyTrashed()->count())
                ->badgeColor('danger'),
            'public' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->withoutTrashed()->where('visibility', NoteVisibility::Public))
                ->badge(Note::withoutTrashed()->where('visibility', NoteVisibility::Public)->count()),
            'private' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->withoutTrashed()->where('visibility', NoteVisibility::Private))
                ->badge(Note::withoutTrashed()->where('visibility', NoteVisibility::Private)->count())
                ->badgeColor('success'),
            'hidden' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->withoutTrashed()->where('visibility', NoteVisibility::Hidden))
                ->badge(Note::withoutTrashed()->where('visibility', NoteVisibility::Hidden)->count())
                ->badgeColor('danger'),
            'restricted' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->withoutTrashed()->where('visibility', NoteVisibility::Restricted))
                ->badge(Note::withoutTrashed()->where('visibility', NoteVisibility::Restricted)->count())
                ->badgeColor('danger'),
        ];
    }

/*
    public function getTitle(): string
    {
        return 'Admin - Notes';
    }
    public function getHeading(): string
    {
        return "Notes";
    }
*/
}

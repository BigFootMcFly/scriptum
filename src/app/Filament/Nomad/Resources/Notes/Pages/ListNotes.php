<?php

namespace App\Filament\Nomad\Resources\Notes\Pages;

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
                ->modifyQueryUsing(fn (Builder $query) => $query->withoutTrashed()->public())
                ->badge(Note::withoutTrashed()->public()->count()),
            'private' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->withoutTrashed()->private())
                ->badge(Note::withoutTrashed()->private()->count())
                ->badgeColor('success'),
            'hidden' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->withoutTrashed()->hidden())
                ->badge(Note::withoutTrashed()->hidden()->count())
                ->badgeColor('danger'),
            'restricted' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->withoutTrashed()->restricted())
                ->badge(Note::withoutTrashed()->restricted()->count())
                ->badgeColor('danger'),
        ];
    }
}

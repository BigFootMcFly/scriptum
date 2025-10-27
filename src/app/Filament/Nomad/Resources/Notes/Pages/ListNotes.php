<?php

namespace App\Filament\Nomad\Resources\Notes\Pages;

use App\Filament\Nomad\Resources\Notes\NoteResource;
use App\Models\Note;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

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
            'all' => $this->makeAllTab(),
            'deleted' => $this->makeDeletedTab(),
            'public' => $this->makePublicTab(),
            'private' => $this->makePrivateTab(),
            'hidden' => $this->makeHiddenTab(),
            'restricted' => $this->makeRestrictedTab(),
        ];
    }

    private function makeAllTab(): Tab
    {
        return Tab::make()
        ->badge(Note::withTrashed()->count())
        ->badgeColor('info');
    }

    private function makeDeletedTab(): Tab
    {
        return Tab::make()
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('deleted_at', '<>', null))
            ->badge(Note::onlyTrashed()->count())
            ->badgeColor('danger');
    }

    private function makePublicTab(): Tab
    {
        return Tab::make()
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->withoutTrashed()->publicOnly())
            ->badge(Note::query()->publicOnly()->withoutTrashed()->count());
    }


    private function makePrivateTab(): Tab
    {
        return Tab::make()
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->withoutTrashed()->privateOnly())
            ->badge(Note::query()->privateOnly()->withoutTrashed()->count())
            ->badgeColor('success');
    }

    private function makeHiddenTab(): Tab
    {
        return Tab::make()
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->withoutTrashed()->hiddenOnly())
            ->badge(Note::withoutTrashed()->hiddenOnly()->count())
            ->badgeColor('danger');
    }

    private function makeRestrictedTab(): Tab
    {
        return Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutTrashed()->restrictedOnly())
            ->badge(Note::query()->withoutTrashed()->restrictedOnly()->count())
            ->badgeColor('danger');
    }

}

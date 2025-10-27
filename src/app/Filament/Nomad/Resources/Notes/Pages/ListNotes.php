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
            ->modifyQueryUsing(fn (Builder $query): Builder => Note::builder($query)->publicOnly()->withoutTrashed())
            ->badge(Note::query()->publicOnly()->withoutTrashed()->count());
    }


    private function makePrivateTab(): Tab
    {
        return Tab::make()
            ->modifyQueryUsing(fn (Builder $query): Builder => Note::builder($query)->privateOnly()->withoutTrashed())
            ->badge(Note::query()->privateOnly()->withoutTrashed()->count())
            ->badgeColor('success');
    }

    private function makeHiddenTab(): Tab
    {
        return Tab::make()
            ->modifyQueryUsing(fn (Builder $query): Builder => Note::builder($query)->hiddenOnly()->withoutTrashed())
            ->badge(Note::withoutTrashed()->hiddenOnly()->count())
            ->badgeColor('danger');
    }

    private function makeRestrictedTab(): Tab
    {
        return Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => Note::builder($query)->restrictedOnly()->withoutTrashed())
            ->badge(Note::query()->withoutTrashed()->restrictedOnly()->count())
            ->badgeColor('danger');
    }

}

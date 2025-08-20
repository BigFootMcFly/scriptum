<?php

namespace App\Filament\Nomad\Resources\Notes\Pages;

use App\Filament\Nomad\Resources\Notes\NoteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNotes extends ListRecords
{
    protected static string $resource = NoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
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

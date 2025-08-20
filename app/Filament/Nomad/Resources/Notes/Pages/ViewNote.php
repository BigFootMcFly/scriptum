<?php

namespace App\Filament\Nomad\Resources\Notes\Pages;

use App\Filament\Nomad\Resources\Notes\NoteResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewNote extends ViewRecord
{
    protected static string $resource = NoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

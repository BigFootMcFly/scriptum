<?php

namespace App\Filament\User\Resources\Notes\Pages;

use App\Filament\User\Resources\Notes\NoteResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

class CreateNote extends CreateRecord
{
    protected static string $resource = NoteResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = User::assure()->id;

        return $data;
    }
}

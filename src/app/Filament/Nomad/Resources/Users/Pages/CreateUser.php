<?php

namespace App\Filament\Nomad\Resources\Users\Pages;

use App\Filament\Nomad\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}

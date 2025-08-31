<?php

namespace App\Filament\User\Resources\Notes\Pages\Auth;

use App\Filament\User\Resources\Notes\NoteResource;
use Filament\Resources\Pages\Page;

class UserRegister extends Page
{
    protected static string $resource = NoteResource::class;

    protected string $view = 'filament.user.resources.notes.pages.auth.user-register';
}

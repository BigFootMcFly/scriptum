<?php

namespace App\Filament\Helpers;

use App\Enums\NoteVisibility;
use Closure;

final class NoteVisibilityColorCallback
{
    public static function make(): Closure
    {
        return fn (NoteVisibility $state): string => match ($state) {
            NoteVisibility::Private => 'success',
            NoteVisibility::Public => 'warning',
            NoteVisibility::Restricted => 'danger',
            NoteVisibility::Hidden => 'gray',
        };
    }
}

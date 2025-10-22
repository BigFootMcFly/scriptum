<?php

namespace App\Actions;

use Filament\Facades\Filament;

final class LogoutUser
{
    public static function execute(?string $redirectTo = null): void
    {
        Filament::auth()->logout();

        session()->invalidate();
        session()->regenerateToken();

        if ($redirectTo === null) {
            $redirectTo = 'filament.user.resources.notes.index';
        }
    }
}

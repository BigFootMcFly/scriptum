<?php

namespace App\Actions\Filament;

use Filament\Actions\Action;
use Filament\Support\Enums\IconSize;

class FullPageViewAction
{
    public static function make(callable $url): Action
    {
        return Action::make('View')
            ->label('Details')
            ->icon('heroicon-o-eye')
            ->iconSize(IconSize::Small)
            ->color('white')
            ->tooltip('Details')
            ->url($url);

    }
}

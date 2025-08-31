<?php

namespace App\Actions\Filament;

use Filament\Actions\ViewAction;
use Filament\Support\Enums\IconSize;

class ModalViewAction
{
    public static function make(): ViewAction
    {
        return ViewAction::make('info')
            ->label('View')
            ->icon('heroicon-o-information-circle')
            ->iconSize(IconSize::Small)
            //->iconButton()
            ->color('white')
            ->tooltip('Quick View')
            ->extraAttributes(
                [
                    'title' => 'Quick View',
                ]
            );
    }
}

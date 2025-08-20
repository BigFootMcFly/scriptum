<?php

namespace App\Filament\Nomad\Resources\Notes\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class NoteInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name'),
                TextEntry::make('visibility'),
                TextEntry::make('title'),
                TextEntry::make('slug'),
                //TODO: make the RichContentRender for the body
                //TextEntry::make('body'),
                //TextEntry::make('body_content'),
                TextEntry::make('deleted_at')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}

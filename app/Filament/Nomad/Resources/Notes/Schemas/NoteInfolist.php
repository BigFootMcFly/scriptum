<?php

namespace App\Filament\Nomad\Resources\Notes\Schemas;

use App\Enums\NoteVisibility;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\View\Components\BadgeComponent;

class NoteInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Metadata')
                    //->collapsed(false)
                    ->collapsible(true)
                    ->columnSpanFull()
                    ->columns(2)
                    ->components([
                        TextEntry::make('user.name')
                        ->label('User')
                    ,
                    TextEntry::make('visibility')
                        ->badge()
                        ->color(fn (NoteVisibility $state): string => match($state) {
                            NoteVisibility::Private => 'success',
                            NoteVisibility::Public => 'warning',
                            NoteVisibility::Restricted => 'danger',
                            NoteVisibility::Hidden => 'gray',
                        })
                    ,
                    TextEntry::make('title'),
                    TextEntry::make('slug'),
                    TextEntry::make('created_at')
                        ->dateTime(),
                    TextEntry::make('updated_at')
                        ->dateTime(),
                        TextEntry::make('deleted_at')
                        ->dateTime()
                        ->placeholder('n/a'),

                    ]),
                    Section::make('Content')
                        ->columnSpanFull()
                        ->columns(1)
                        ->components([
                        //TODO: make the RichContentRender for the body
                        TextEntry::make('body')
                            ->hiddenLabel()
                        ,
                        //TextEntry::make('body_content'),
                    ])

            ]);
    }
}

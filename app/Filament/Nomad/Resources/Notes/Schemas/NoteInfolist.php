<?php

namespace App\Filament\Nomad\Resources\Notes\Schemas;

use App\Filament\Helpers\NoteVisibilityColorCallback;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;

class NoteInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Properties')
                    //->icon('heroicon-o-list-bullet')
                    ->icon(Heroicon::ListBullet)
                    ->iconColor(Color::Emerald)
                    ->collapsed(true)
                    ->collapsible(true)
                    ->columnSpanFull()
                    ->columns(2)
                    ->components([
                        TextEntry::make('user.name')
                        ->label('User')
                    ,
                    TextEntry::make('visibility')
                        ->badge()
                        ->color(NoteVisibilityColorCallback::make())
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
                        ->icon('heroicon-o-document')
                        ->iconColor(Color::Emerald)
                        ->collapsible()
                        ->columnSpanFull()
                        ->columns(1)
                        ->components([
                        TextEntry::make('body')
                            ->extraAttributes(['class'=>'fi-prose'])
                            ->hiddenLabel()
                        ,
                        //NOTE: maybe for debugging add a collapsed section with the body_content to the form
                        //TextEntry::make('body_content'),
                    ])

            ]);
    }
}

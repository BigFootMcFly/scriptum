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
                    // ->heading(heading: fn ($record) => $record->title)
                    // ->description('There is no place like 127.0.0.1')
                    // ->description(fn ($record) => $record->title)
                    ->afterHeader([
                        TextEntry::make('visibility')
                            ->hiddenLabel()
                            ->badge()
                            ->color(NoteVisibilityColorCallback::make())
                            ->extraAttributes([
                                'x-show' => 'isCollapsed', // visible only when section is collapsed
                                'x-cloak' => true,         // prevent FOUC before Alpine boots
                                // 'x-transition.duration.500ms', // does not work, @see: https://alpinejs.dev/directives/transition
                            ]),
                        // TODO: add an icon which shows if the Note is deleted or not
                    ])
                    // TODO: maybe creat a custom section here, in which it is collapsed, show minimal info with badges,
                    //      and a traditional full list if not collapsed...
                    // ->icon('heroicon-o-list-bullet')
                    ->icon(Heroicon::ListBullet)
                    ->iconColor(Color::Emerald)
                    ->collapsed(true)
                    ->collapsible(true)
                    ->columnSpanFull()
                    ->columns(2)
                    ->components([
                        TextEntry::make('user.name')
                            ->color('info')
                            ->label('User'),
                        TextEntry::make('visibility')
                            ->badge()
                            ->color(NoteVisibilityColorCallback::make()),
                        TextEntry::make('title')
                            ->color('info'),
                        TextEntry::make('slug')
                            ->color('info'),
                        TextEntry::make('created_at')
                            ->color('info')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->color('info')
                            ->dateTime(),
                        TextEntry::make('deleted_at')
                            ->dateTime()
                            ->color('danger')
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
                            ->extraAttributes(['class' => 'fi-prose'])
                            ->hiddenLabel(),
                    ]),
                Section::make('Search index')
                    ->icon('heroicon-o-magnifying-glass-circle')
                    ->iconColor(Color::Emerald)
                    ->collapsible()
                    ->collapsed()
                    ->columnSpanFull()
                    ->components([
                        TextEntry::make('body_content')
                            ->hiddenLabel(),
                    ]),

            ]);
    }
}

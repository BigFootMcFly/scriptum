<?php

namespace App\Filament\User\Resources\Notes\Schemas;

use App\Enums\NoteVisibility;
use App\Models\Note;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class NoteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
/*                Hidden::make('user_id')
                    ->default(auth()->user()->id),*/
/*                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),*/
                Select::make('visibility')
                    ->options(NoteVisibility::class)
                    ->default('private')
                    ->required(),
                TextInput::make('title')
                    ->required()
                    ->minLength(3)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->required()
                    ->unique(Note::class, 'slug'),
                RichEditor::make('body')
                    ->json()
                    ->fileAttachmentsVisibility('private')
                    ->columnSpanFull()
                    ->activePanel('customBlocks')
                    ->toolbarButtons([
                        ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript'],
                        ['clearFormatting'],
                        ['details'],
                        ['h1', 'h2', 'h3', 'alignStart', 'alignCenter', 'alignEnd'],
                        ['blockquote', 'bulletList', 'orderedList', 'horizontalRule'],
                        ['link'],
                        ['table', 'attachFiles', 'mergeTags', 'customBlocks'], // The `customBlocks` and `mergeTags` tools are also added here if those features are used.
                        ['undo', 'redo'],
                    ])
            ]);
    }
}

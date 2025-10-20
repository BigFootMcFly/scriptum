<?php

namespace App\Filament\User\Resources\Notes\Schemas;

use App\Enums\NoteVisibility;
use App\Filament\Forms\Components\RichEditor\RichContentCustomBlocks\CodeBlock;
use App\Models\Note;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
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
                Select::make('visibility')
                    ->options(NoteVisibility::userEditable())
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
                SpatieTagsInput::make('form_tags')
                    ->dehydrated(true)
                    ->label('Tags'),
                RichEditor::make('body')
                    ->json()
                    ->fileAttachmentsVisibility('private')
                    ->customBlocks([
                        CodeBlock::class,
                    ])
                    ->columnSpanFull()
                    ->activePanel('customBlocks')
                    ->toolbarButtons([
                        ['textColor', 'bold', 'italic', 'underline', 'strike', 'subscript', 'superscript'],
                        ['clearFormatting'],
                        ['details'],
                        ['h1', 'h2', 'h3', 'alignStart', 'alignCenter', 'alignEnd'],
                        ['grid', 'gridDelete'],
                        ['blockquote', 'bulletList', 'orderedList', 'horizontalRule'],
                        ['link'],
                        ['table', 'attachFiles', 'mergeTags', 'customBlocks'], // The `customBlocks` and `mergeTags` tools are also added here if those features are used.
                        ['undo', 'redo'],
                    ])
                    ->customTextColors()
            ]);
    }
}

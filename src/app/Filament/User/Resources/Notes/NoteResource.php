<?php

namespace App\Filament\User\Resources\Notes;

use App\Filament\User\Resources\Notes\Pages\CreateNote;
use App\Filament\User\Resources\Notes\Pages\EditNote;
use App\Filament\User\Resources\Notes\Pages\ListNotes;
use App\Filament\User\Resources\Notes\Pages\ViewNote;
use App\Filament\User\Resources\Notes\Schemas\NoteForm;
use App\Filament\User\Resources\Notes\Schemas\NoteInfolist;
use App\Filament\User\Resources\Notes\Tables\NotesTable;
use App\Models\Note;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NoteResource extends Resource
{
    protected static ?string $model = Note::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return NoteForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return NoteInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NotesTable::configure($table);
    }

    /**
     * @return Builder<Note>
     */
    public static function getEloquentQuery(): Builder
    {

        $query = parent::getEloquentQuery();

        if (auth()->check()) {
            $query->with('user'); // NOTE: prevent duplicate queries @see: App/Models/Note.php:106
            $query->where('user_id', User::assure()->id);
        }

        // NOTE: add this if the resource table should be filtered by the top search as well...
        $forntPageSearch = session()->get('front-page-search', '');
        if ($forntPageSearch !== '') {
            /** @var Builder<Note> $query */
            $query->search($forntPageSearch, true);
        }

        $query->orderBy('updated_at', 'DESC');

        return $query;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNotes::route('/'),
            'create' => CreateNote::route('/create'),
            // 'view' => ViewNote::route('/{record}'),
            'show' => ViewNote::route('/{record}'),
            'edit' => EditNote::route('/{record}/edit'),
        ];
    }

    /**
     * @return Builder<Note>
     */
    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check();
    }

    public static function getNavigationLabel(): string
    {
        return __('Manage My Notes');
    }
}

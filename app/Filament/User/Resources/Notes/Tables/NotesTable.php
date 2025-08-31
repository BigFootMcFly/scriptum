<?php

namespace App\Filament\User\Resources\Notes\Tables;

use App\Actions\Filament\FullPageViewAction;
use App\Actions\Filament\ModalViewAction;
use App\Filament\Helpers\NoteVisibilityColorCallback;
use App\Models\Note;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;

class NotesTable
{
    //protected ?string $maxContentWidth = 'full';

    public static function getViewNoteUrl(Note $note): string
    {
        return route('filament.user.resources.notes.show', [
            'record' => $note,
        ]);
    }


    public static function configure(Table $table): Table
    {
        return $table
            ->recordClasses(fn (Note $record) => match ($record->trashed()) {
                true => 'border-l-5 !border-l-danger-300 dark:!border-l-danger-900 bg-red-300/20 dark:bg-red-950/20',
                false => 'border-l-5 border-l-green-300 dark:!border-l-green-900 bg-green-300/20 dark:bg-green-950/20',
            })
            //->extraAttributes(['class'=>'fi-width-5xl'])
            ->columns([
                TextColumn::make('visibility')
                    ->badge()
                    ->sortable()
                    ->color(NoteVisibilityColorCallback::make()),
                TextColumn::make('title')
                    ->sortable(),
                TextColumn::make('slug')
                    ->sortable(),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ModalViewAction::make(),
                FullPageViewAction::make(self::getViewNoteUrl(...)),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ;
    }
}

<?php

namespace App\Filament\User\Resources\Notes\Tables;

use App\Actions\Filament\FullPageViewAction;
use App\Actions\Filament\ModalViewAction;
use App\Enums\NoteVisibility;
use App\Filament\Helpers\NoteVisibilityColorCallback;
use App\Models\Note;
use Closure;
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
            /*->recordClasses(fn (Note $record) => match ($record->trashed()) {
                true => 'border-l-5 !border-l-danger-300 dark:!border-l-danger-900 bg-red-300/20 dark:bg-red-950/20',
                false => 'border-l-5 border-l-green-300 dark:!border-l-green-900 bg-green-300/20 dark:bg-green-950/20',
            })*/
/*
            ->recordClasses(function (Note $record): string {

                if ($record->isAdminRestricted()) {
                    return 'border-l-5 !border-l-gray-300 dark:!border-l-gray-900 bg-gray-500/20 dark:bg-gray-950/20 opacity-50 blur-[1px]';
                }

                if ($record->trashed()) {
                    return 'border-l-5 !border-l-danger-300 dark:!border-l-danger-900 bg-red-300/20 dark:bg-red-950/20';
                }

                return 'border-l-5 border-l-green-300 dark:!border-l-green-900 bg-green-300/20 dark:bg-green-950/20';
            })
*/
/*
            ->recordClasses(function (Note $record): string {
                if ($record->isAdminRestricted()) {
                    return 'note-row-restricted';
                }
                if ($record->trashed()) {
                    return 'note-row-trashed';
                }
                return 'note-row';
            })
*/
            ->recordClasses(fn (Note $record): string => match(true) {
                    $record->isAdminRestricted() => 'note-row-restricted',
                    $record->trashed() => 'note-row-trashed',
                    default => 'note-row',
            })
            //->extraAttributes(['class'=>'fi-width-5xl'])
            ->columns([
                TextColumn::make('visibility')
                    ->badge()
                    ->sortable()
                    ->color(NoteVisibilityColorCallback::make()),
                TextColumn::make('title')
                    ->sortable()
                    ->limit(50),
                TextColumn::make('slug')
                    ->sortable()
                    ->limit(50),
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
            ->heading(fn () =>view('front-page.notes.table-heading'))
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordUrl(
                fn (Note $record): string => match ($record->visibility) {
                    NoteVisibility::Hidden,
                    NoteVisibility::Restricted => '',
                    default => route('filament.user.resources.notes.edit', ['record' => $record]),
                }
            )
            ->recordActions([
                ModalViewAction::make()
                    ->visible(static::actionAllowedByVisibility()),
                FullPageViewAction::make(self::getViewNoteUrl(...))
                    ->visible(static::actionAllowedByVisibility()),
                EditAction::make()
                    ->visible(static::actionAllowedByVisibility()),
            ])
            ->toolbarActions([])
            ;
    }

    public static function actionAllowedByVisibility(): Closure
    {
        return fn(Note $note): bool => match ($note->visibility) {
            NoteVisibility::Hidden,
            NoteVisibility::Restricted => false,
            default => true,
        };
    }
}

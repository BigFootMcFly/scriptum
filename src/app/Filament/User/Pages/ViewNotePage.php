<?php

namespace App\Filament\User\Pages;

use App\Models\Note;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class ViewNotePage extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament.user.pages.view-note-page';

    protected ?string $heading = '';

    public Note $note;

    public function mount(string $user, string $slug): void
    {
        $this->note = Note::with('user')->where('slug', "{$user}/{$slug}")->firstOrFail();
    }

    public function getTitle(): string | Htmlable
    {
        return $this->note->title;
    }

}

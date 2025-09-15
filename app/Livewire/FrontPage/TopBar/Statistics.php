<?php

namespace App\Livewire\FrontPage\TopBar;

use App\Models\Note;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\On;
use Livewire\Component;

class Statistics extends Component
{

    public int $noteCount;

    public int $ownCount;

    public int $ownPrivateCount;

    public int $ownPublicCount;

    public int $otherPublicDount;

    public function mount(): void
    {
        // on the note resource pages use only our own notes
        $routeIsNoteResourrce = Route::is('filament.user.resources.notes.*');
        $this->queryStatistics( $routeIsNoteResourrce );
    }

    #[On('front-page-updated')]
    public function onFrontPageUpdated(): void
    {
        $this->queryStatistics();
    }

    protected function queryStatistics(bool $ownedOnly = false): void
    {
        $user = auth()?->user();
        $userId = $user?->id ?? 0;
        $search = session('front-page-search', '');

        $query = Note::query()->frontPage($user);

        if ('' !== $search) {
            $query->search($search, true);
        }

        // only get owned note statistics
        if ($user && $ownedOnly) {
            $query->owned($user);
        }

        $query->statistics($userId);

        $stats = $query->first()->toArray();

        //TODO: make a DTO for this
        $this->noteCount = $stats['total'];
        $this->ownCount = $stats['own_notes'];
        $this->ownPublicCount = $stats['own_public_notes'];
        $this->ownPrivateCount = $stats['own_private_notes'];
        $this->otherPublicDount = $stats['other_public_notes'];

    }
/*
    protected function queryStatistics___OLD(): void
    {
        $userId = auth()?->user()?->id ?? 0;

        //TODO: the search term should be handled the same as in the scope (tokenized, partials added, etc)

        $search = session('front-page-search', '');

        // base query without  bm25 and highlight
        $query = Note::query()
            ->join('notes_fts', 'notes.id', '=', 'notes_fts.rowid')
            ->whereRaw('(notes.user_id = ? OR notes.visibility = "public")', [$userId])
            ->when($search, fn ($q) =>
                $q->whereRaw('notes_fts MATCH ?', [$search . '*'])
            )
            ->whereNull('notes.deleted_at');

        // add ststistics
        $stats = $query->selectRaw('
            COUNT(*) as total,
            COUNT(CASE WHEN notes.user_id = ? THEN 1 END) as own_notes,
            COUNT(CASE WHEN notes.visibility = "public" AND notes.user_id = ? THEN 1 END) as own_public_notes,
            COUNT(CASE WHEN notes.visibility = "private" AND notes.user_id = ? THEN 1 END) as own_private_notes,
            COUNT(CASE WHEN notes.visibility = "public" AND notes.user_id != ? THEN 1 END) as other_public_notes
        ', [$userId, $userId, $userId, $userId])
        ->first()
        ->toArray();

        //TODO: make a DTO for this
        $this->noteCount = $stats['total'];
        $this->ownCount = $stats['own_notes'];
        $this->ownPublicCount = $stats['own_public_notes'];
        $this->ownPrivateCount = $stats['own_private_notes'];
        $this->otherPublicDount = $stats['other_public_notes'];

    }
*/
    public function render()
    {
        return view('livewire.front-page.top-bar.statistics');
    }
}

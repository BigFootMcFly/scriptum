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

    // NOTE: in Admin VIewingMode the numbers are incorect !!!
    protected function queryStatistics(bool $ownedOnly = false): void
    {
        $user = auth()->user();
        $userId = $user->id ?? 0;
        $search = session()->get('front-page-search', '');

        // @phpstan-ignore-next-line
        $query = Note::query()->frontPage($user);

        if ($search !== '') {
            $query->search($search, true);
        }

        // only get owned note statistics
        if ($user && $ownedOnly) {
            $query->owned($user);
        }

        $query->statistics($userId);

        $stats = $query->first()->toArray();

        // TODO: make a DTO for this
        $this->noteCount = $stats['total'];
        $this->ownCount = $stats['own_notes'];
        $this->ownPublicCount = $stats['own_public_notes'];
        $this->ownPrivateCount = $stats['own_private_notes'];
        $this->otherPublicDount = $stats['other_public_notes'];

    }

    public function render()
    {
        return view('livewire.front-page.top-bar.statistics');
    }
}

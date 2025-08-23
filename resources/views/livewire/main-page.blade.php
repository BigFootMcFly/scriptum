<div class="space-y-6">
    <!-- Search / Filters -->
    <div class="flex gap-2">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Search notes..."
            class="border rounded px-2 py-1"
        />
        <input type="checkbox" wire:model.live="partial" class="border rounded px-2 py-1">Partial</input>
    </div>

    <!-- List -->
    <div class="space-y-4">
        @forelse($notes as $note)
            <livewire:main-page.note :note="$note" :key="$note->id" />
        @empty
            <p>No notes found.</p>
        @endforelse
    </div>

    <!-- Pagination links -->
    <div>
        {{ $notes->links() }}
    </div>

</div>

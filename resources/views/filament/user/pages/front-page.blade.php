<x-filament-panels::page>
    {{-- Common components --}}
    <livewire:front-page.back-to-top-button></livewire:front-page.back-to-top-button>

    {{-- Note list --}}
    <div class="divide-y divide-gray-200 dark:divide-gray-800">
        @foreach ($this->notes as $note)
            <livewire:view-note.note :note="$note" :key="$note->id"></livewire:view-note.note>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        <x-filament::pagination :paginator="$this->notes" />
    </div>
</x-filament-panels::page>

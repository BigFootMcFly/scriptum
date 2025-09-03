<x-filament-panels::page>
    <livewire:edit-note-modal-form></livewire:edit-note-modal-form>
    <livewire:front-page.back-to-top-button></livewire:front-page.back-to-top-button>

    <div class="divide-y divide-gray-200 dark:divide-gray-800">
        @foreach ($this->notes() as $note)
            <livewire:view-note.note :note="$note" :key="$note->id"></livewire:view-note.note>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        <x-filament::pagination :paginator="$this->notes()" />
    </div>

</x-filament-panels::page>

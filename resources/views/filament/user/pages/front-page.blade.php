<x-filament-panels::page>
    <livewire:edit-note-modal-form></livewire:edit-note-modal-form>

    <div class="divide-y divide-gray-200 dark:divide-gray-800">
        @foreach ($this->notes() as $note)
            <livewire:view-note.note :note="$note" :key="$note->id"></livewire:view-note.note>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        <x-filament::pagination :paginator="$this->notes()" />
    </div>

    @push('scripts')
        <script>
            window.addEventListener('scroll-to-top', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        </script>
    @endpush

</x-filament-panels::page>

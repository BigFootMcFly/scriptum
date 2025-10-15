<x-filament-panels::page>
    {{-- Pagination --}}
    <nav aria-label="Pagination navigation" role="navigation" class="">
        <x-filament::pagination :paginator="$this->notes" />
    </nav>

    <div class="fi-layout">
    {{-- Common components --}}
        <livewire:front-page.back-to-top-button></livewire:front-page.back-to-top-button>
    {{--
        <livewire:edit-note-modal-form></livewire:edit-note-modal-form>
    --}}

        {{-- ModalNoteEditor - Modal begin --}}
        {{ $this->getModalView() }}
        {{-- ModalNoteEditor - Modal end --}}

        {{-- Note list --}}
        <div class="divide-y divide-gray-200 dark:divide-gray-800">
            @foreach ($this->notes as $note)
                <livewire:view-note.note :note="$note" :key="$note->id"></livewire:view-note.note>
            @endforeach
        </div>

    </div>

    {{-- Pagination --}}
    <nav aria-label="Pagination navigation" role="navigation" class="">
        <x-filament::pagination :paginator="$this->notes" />
    </nav>

    <livewire:page-footer></livewire:page-footer>
</x-filament-panels::page>

<x-filament-panels::page>
    <livewire:edit-note-modal-form></livewire:edit-note-modal-form>
    @livewire('view-note.note', ['note' => $note])
</x-filament-panels::page>

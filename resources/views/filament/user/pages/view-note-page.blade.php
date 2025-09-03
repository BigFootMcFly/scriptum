<x-filament-panels::page>
    <livewire:edit-note-modal-form></livewire:edit-note-modal-form>
    <livewire:front-page.back-to-top-button></livewire:front-page.back-to-top-button>
    @livewire('view-note.note', ['note' => $note])
</x-filament-panels::page>

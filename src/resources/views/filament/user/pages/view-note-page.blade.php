<x-filament-panels::page>
    <livewire:front-page.back-to-top-button></livewire:front-page.back-to-top-button>
    @livewire('view-note.note', ['note' => $note])
    <livewire:edit-note-modal-form></livewire:edit-note-modal-form>
</x-filament-panels::page>

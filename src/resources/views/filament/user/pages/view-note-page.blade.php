<x-filament-panels::page>
    <livewire:front-page.back-to-top-button></livewire:front-page.back-to-top-button>
    @livewire('view-note.note', ['note' => $note])
    <livewire:page-footer></livewire:page-footer>
</x-filament-panels::page>

<x-filament-panels::page>
    <livewire:front-page.back-to-top-button></livewire:front-page.back-to-top-button>
    @livewire('view-note.note', ['note' => $note])
    {{ $this->getModalView() }}
</x-filament-panels::page>

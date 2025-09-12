<div>
    {{-- Modal begin --}}
    <x-filament::modal id="edit-note" width="5xl">
        <x-slot name="heading">Edit Note</x-slot>

        {{ $this->form }}

        <x-slot name="footer">
            <x-filament::button wire:click="saveNote" color="primary">
                Save
            </x-filament::button>
            <x-filament::button wire:click="$dispatch('close-modal', { id: 'edit-note' })" color="gray">
                Cancel
            </x-filament::button>
        </x-slot>
    </x-filament::modal>
    {{-- Modal end --}}
</div>
<x-filament-panels::page>
    <div class="space-y-6">
        @foreach ($this->notes() as $note)
            <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-2xl">
                <h2 class="text-xl font-semibold">{{ $note->title }}</h2>
                <p class="mt-2 text-gray-700 dark:text-gray-300">
                    {{ $note->content }}
                </p>

                <x-filament::button
                    color="primary"
                    class="mt-4"
                    wire:click="openEditModal({{ $note->id }})"
                >
                    Edit
                </x-filament::button>
            </div>
        @endforeach

        <div>
            {{ $this->notes()->links() }}
        </div>
    </div>

    {{-- Modal --}}
    <x-filament::modal id="edit-note" width="2xl">
        <x-slot name="heading">Edit Note</x-slot>

        {{ $this->form }}

        <x-slot name="footer">
            <x-filament::button wire:click="save" color="primary">
                Save
            </x-filament::button>
        </x-slot>
    </x-filament::modal>
</x-filament-panels::page>

<x-filament-panels::page>
    <div class="space-y-6">
        @foreach ($this->notes() as $note)
            <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-2xl">
                <div class="header">

{{--
                <x-filament::button
                    color="primary"
                    class="mt-4 float-end"
                    wire:click="openEditModal({{ $note->id }})"
                >
                    Edit
                </x-filament::button>
--}}
                <x-filament::link
                    wire:click="openEditModal({{ $note->id }})"
                    icon="heroicon-m-pencil-square"
                    tooltip="Edit this Note"
                >
                Edit Note
            </x-filament::link>
            <h2 class="text-xl font-semibold">{{ $note->title }}</h2>

                <hr style="color: darkgray;">
                </div>
                <p class="mt-2 text-gray-700 dark:text-gray-300">
                    @php
                        //dd($note);
                    @endphp
                    {!! $note->renderRichContent('body') !!}
                </p>
                <div class="body">

                </div>
            </div>
        @endforeach

        <hr class="mt-2 mb-2" style="margin-top:1em; margin-bottom:1em;">
        <x-filament::pagination
            :paginator="$this->notes()"
        />
    </div>


    <div class="note mt-8">
        <div class="note_header w-full p-4 border border-gray-200 bg-gray-50 rounded-t-xl dark:border-gray-600 dark:bg-gray-700">
            Note header
        </div>
        <div class="note_body">
            <p>Note body</p>
        </div>
        <div class="note_footer">
            Note footer
        </div>
    </div>

    {{-- Modal --}}
    <x-filament::modal id="edit-note" width="5xl">
        <x-slot name="heading">Edit Note</x-slot>

        {{ $this->form }}

        <x-slot name="footer">
            <x-filament::button wire:click="save" color="primary">
                Save
            </x-filament::button>
        </x-slot>
    </x-filament::modal>


</x-filament-panels::page>

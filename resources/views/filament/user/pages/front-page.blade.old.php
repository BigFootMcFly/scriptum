<x-filament-panels::page>
    <div class="space-y-6">
        @foreach ($this->notes() as $note)
            <div class="relative rounded-2xl border bg-white dark:bg-zinc-900 shadow-sm p-4 border-zinc-800">

                {{-- Edit button --}}
                <div class="absolute top-2 right-2">
                    <x-filament::icon-button
                        icon="heroicon-m-pencil-square"
                        tag="a"
                        wire:click="openEditModal({{ $note->id }})"
                        size="sm"
                        color="gray"
                        label="Edit"
                        tooltip="Edit the note"
                    />
                </div>

                {{-- Title --}}
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    {{ $note->title }}
                </h2>

                {{-- Body --}}
                <div class="mt-2 text-sm leading-relaxed text-gray-700 dark:text-gray-300">
                    {!! $note->renderRichContent('body') !!}
                </div>

                {{-- Permalink --}}
                <div class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                    <a href="{{ route('filament.user.resources.notes.view', $note) }}" class="hover:underline">
                        permalink
                    </a>
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

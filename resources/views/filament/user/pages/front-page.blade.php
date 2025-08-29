<x-filament-panels::page>



<div class="flex items-center w-full">
    <!-- Left group (3 divs) -->
    <div class="flex space-x-2">
        <div class="px-2 py-1 bg-gray-200">Left 1</div>
        <div class="px-2 py-1 bg-gray-200">Left 2</div>
        <div class="px-2 py-1 bg-gray-200">Left 3</div>
        <div class="px-2 py-1 bg-gray-200">Left 4</div>
    </div>

    <!-- Middle (fills remaining space) -->
    <div class="flex-1 px-2 text-center bg-gray-800">
        <x-filament::input.wrapper>
            <x-filament::input
                type="text"
                {{--wire:model="name"--}}
            />
        </x-filament::input.wrapper>

    </div>

    <!-- Right -->
    <div class="px-2 py-1 bg-gray-200">
        Right
    </div>
</div>













    <div class="divide-y divide-gray-200 dark:divide-gray-800">
        @foreach ($this->notes() as $note)
            <div class="py-4">
                {{-- Header --}}
                <div class="p-1 ">
                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        <a href="{{ route('filament.user.resources.notes.view', $note) }}" class="hover:underline">
                            {{ $note->user->handle }}
                        </a>
                    </div>
                    <div class="flex justify-between items-start">
                        <h1 class="font-semibold text-gray-900 dark:text-gray-100 text-2xl">
                            {{ $note->title }}
                        </h1>

                        @can('update', $note)

                        {{-- Edit button --}}
                        <x-filament::icon-button
                            icon="heroicon-m-pencil-square"
                            tag="a"
                            wire:click="openEditModal({{ $note->id }})"
                            size="sm"
                            color="warning"
                            label="Edit"
                            tooltip="Edit Note"
                            class="grayscale hover:grayscale-0 duration-1000 cursor-pointer"
                        />
                        @endcan
                    </div>
                </div>


                {{-- Body (truncated height, scroll if too long) --}}
                <div class="p-1">
                    <div class="
                        mt-4
                        overflow-scroll
                        text-sm
                        leading-relaxed
                        text-gray-900
                        dark:text-gray-400
                        fi-prose
                        rounded-xl
                        border-0
                        border-sky-950
                        p-2

                        ">
                        {!! $note->renderRichContent('body') !!}
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex justify-between items-start py-2">
                    <span class="mt-2 text-xs text-gray-500 dark:text-gray-700">{{ $note->created_at}}</span>
                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        <x-filament::link
                            icon="heroicon-m-arrow-top-right-on-square"
                            class="hover:underline text-xs transition duration-500 brightness-50 hover:brightness-100"
                            size="sm"
                            color="gray"
                            href="{{ route('filament.user.resources.notes.view', $note) }}"
                            target="_blank"
                        >
                            permalink
                        </x-filament::link>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        <x-filament::pagination :paginator="$this->notes()" />
    </div>

    {{-- Modal --}}
    <x-filament::modal id="edit-note" width="5xl">
        <x-slot name="heading">Edit Note</x-slot>

        {{ $this->form }}

        <x-slot name="footer">
            <x-filament::button wire:click="saveNote" color="primary">
                Save
            </x-filament::button>
            <x-filament::button wire:click="cancelEditForm" color="gray">
                Cancel
            </x-filament::button>
        </x-slot>
    </x-filament::modal>


</x-filament-panels::page>

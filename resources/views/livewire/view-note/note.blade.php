<div @class([
        "divide-y divide-gray-200 dark:divide-gray-800  divide-double",
        "animate-note-updated" => $pulse,
    ])
>
    <div class="py-4">
        {{-- Header --}}
        <div class="p-1">
            <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                <a href="{{ $note->user->permalink }}"
                    @class([
                        "hover:underline",
                        "text-amber-500" => ($note->user_id === auth()?->user()?->id),
                        "text-teal-500" => ($note->user_id !== auth()?->user()?->id)
                    ])
                >
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
                    wire:click="$dispatch('edit-note', { note: {{ $note->id }} })"
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
                    href="{{ $note->permalink }}"
                >
                    permalink
                </x-filament::link>
            </div>
        </div>
    </div>
</div>

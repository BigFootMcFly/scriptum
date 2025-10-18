@use('App\Enums\NoteVisibility')
<div @class([
        "divide-y divide-gray-200 dark:divide-gray-800 divide-double sm:px-3 lg:px-5",
        "animate-note-updated" => $pulse,
    ])
    class=""
>
    <div class="py-4">
        {{-- Header --}}
        <div class="p-1">
            <div class="mt-2 text-sm text-gray-500 dark:text-gray-400 flex items-center">
                <x-filament::avatar
                    @class([
                        "dark:invert-75 opacity-70" => !$user->hasAvatar()
                    ])
                    class="mr-2"
                    src="{{ asset($note->user->avatar_url) }}"
                    alt="{{ $note->user->name }}"
                    size="w-6 h-6"
                />
                <a href="{{ $note->user->permalink }}"
                    @class([
                        "hover:underline text-md",
                        "text-amber-500" => ($note->user_id === auth()?->user()?->id),
                        "text-teal-500" => ($note->user_id !== auth()?->user()?->id)
                    ])
                >
                    {{ $note->user->handle }}
                    @if ($note->visibility == NoteVisibility::Private)
                        <x-filament::badge size="sm" color="info">private</x-filament::badge>
                    @endif
                </a>
                {{-- <x-note.permalink :note=$note/> --}}

                @if (auth()?->user()?->can('updateOnFrontPage', $note))
                    {{-- Edit button --}}
                    <x-filament::icon-button
                        icon="heroicon-m-pencil-square"
                        tag="a"
                        wire:click="$dispatch('edit-note', { note: {{ $note->id }} })"
                        size="xs"
                        color="warning"
                        label="Edit"
                        tooltip="Edit Note"
                        class="grayscale hover:grayscale-0 duration-300 cursor-pointer ml-auto"
                    />
                @endif
            </div>
            <div class="flex justify-between items-start">
                <h1 class="font-semibold text-gray-900 dark:text-gray-100 text-2xl">
                    {{ $note->title }}
                </h1>
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
                dark:border-zinc-800 border-zink-100
                p-2
                ">
                {!! $note->renderRichContent('body') !!}
            </div>
        </div>

        {{-- Footer --}}
        <div class="flex justify-between items-start py-2">
            <span class="mt-2 text-xs text-gray-500 dark:text-gray-600">
                {{ $note->created_at}}
            </span>
            <x-note.permalink :note=$note/>
        </div>
    </div>
</div>

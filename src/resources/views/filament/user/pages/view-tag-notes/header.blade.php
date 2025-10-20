@use('App\Enums\NoteVisibility')
<div class="flex align-middle border-b-1 border-zinc-900 pb-2">
    @php
        $noteCount = $notes->count();
        $noteLabel = match($noteCount==1) {
            true => e('matching note'),
            default => e('matching notes')
        };
    @endphp

    <div class="flex flex-col ml-2">
        <div class="font-semibold text-gray-900 dark:text-gray-100 text-2xl">
            <span class="opacity-50">Tag: </span>
            #{{ $tag->name }}</div>
        <div class="text-zinc-500 dark:text-zinc-500">
            <span>{{ $noteCount }}</span>
                {{ $noteLabel }}
            </div>

    </div>
    <div class="ml-auto text-6xl italic font-bold text-zinc-300 dark:text-zinc-700">
            Notes
    </div>

</div>

@use('App\Enums\NoteVisibility')
<div class="flex align-middle border-b-1 border-zinc-900 pb-2">
    <x-filament::avatar
        @class([
            "dark:invert-75 opacity-70" => !$user->hasAvatar()
        ])
        src="{{ asset($user->avatar_url) }}"
        alt="{{ $user->name }}"
        size="w-14 h-14"
    />
    @php
        $publicNoteCount = $user->notes->where('visibility', NoteVisibility::Public)->count();
        $noteLabel = match($publicNoteCount==1) {
            true => e('public note'),
            default => e('public notes')
        };
    @endphp

    <div class="flex flex-col ml-2">
        <div class="font-semibold text-gray-900 dark:text-gray-100 text-2xl">{{ $user->name }}</div>
        <div class="text-zinc-500 dark:text-zinc-500">
            <span>{{ $publicNoteCount }}</span>
            {{ $noteLabel }}

            </div>

    </div>
    <div class="ml-auto text-6xl italic font-bold text-zinc-300 dark:text-zinc-700">
            Notes
    </div>

</div>

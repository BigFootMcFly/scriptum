<div class="flex space-x-1 items-center pb-0.5 bg-inherit flex justify-center">
    {{-- all notes --}}
    <span class="flex items-center space-x-0.5 text-sky-800">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24">
            <path fill="currentColor" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 3.99H8a4 4 0 0 0-4 4v10a4 4 0 0 0 4 4h8a4 4 0 0 0 4-4v-10a4 4 0 0 0-4-4ZM9 2v5M15 2v5M8 16h6M8 12h8"/>
        </svg>
        <span>{{ $noteCount }}</span>
        <span>{{ __('Notes') }},</span>
    </span>
    {{-- own notes --}}
    <span class="flex items-center space-x-0.5 text-orange-800">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24">
            <path fill="currentColor" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 3.99H8a4 4 0 0 0-4 4v10a4 4 0 0 0 4 4h8a4 4 0 0 0 4-4v-10a4 4 0 0 0-4-4ZM9 2v5M15 2v5M8 16h6M8 12h8"/>
        </svg>
        <span class="">{{ $ownCount }}</span>
        <span>{{ __('Own notes') }},</span>
    </span>
    {{-- own private notes --}}
    <span class="flex items-center space-x-0.5 text-green-800">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24">
            <path fill="currentColor" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 3.99H8a4 4 0 0 0-4 4v10a4 4 0 0 0 4 4h8a4 4 0 0 0 4-4v-10a4 4 0 0 0-4-4ZM9 2v5M15 2v5M8 16h6M8 12h8"/>
        </svg>
        <span  class="">{{ $ownPrivateCount }}</span>
        <span>{{ __('Own private notes') }},</span>
    </span>
    {{-- own pubic notes --}}
    <span class="flex items-center space-x-0.5 text-amber-800">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24">
            <path fill="currentColor" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 3.99H8a4 4 0 0 0-4 4v10a4 4 0 0 0 4 4h8a4 4 0 0 0 4-4v-10a4 4 0 0 0-4-4ZM9 2v5M15 2v5M8 16h6M8 12h8"/>
        </svg>
        <span class="">{{ $ownPublicCount }}</span>
        <span>{{ __('Own public notes') }}</span>
    </span>
</div>

<div class="flex space-x-1 items-center pb-0.5">

    {{-- all notes --}}
    <span class="flex items-center space-x-0.5">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"><path stroke="#292D32" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5" d="M8 2v3M16 2v3M21 8.5V17c0 3-1.5 5-5 5H8c-3.5 0-5-2-5-5V8.5c0-3 1.5-5 5-5h8c3.5 0 5 2 5 5Z"/><path stroke="#292D32" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5" d="M8 11h8M8 16h4" opacity=".4"/></svg>
        <span>{{ $noteCount }}</span>
        <span>{{ __('Notes') }},</span>
    </span>
    {{-- own notes --}}
    <span class="flex items-center space-x-0.5">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"><path stroke="#292D32" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5" d="M8 2v3M16 2v3M21 8.5V17c0 3-1.5 5-5 5H8c-3.5 0-5-2-5-5V8.5c0-3 1.5-5 5-5h8c3.5 0 5 2 5 5Z"/><path stroke="#292D32" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5" d="M8 11h8M8 16h4" opacity=".4"/></svg>
        <span>{{ $ownCount }}</span>
        <span>{{ __('Own notes') }},</span>
    </span>
    {{-- own private notes --}}
    <span class="flex items-center space-x-0.5">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"><path stroke="#292D32" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5" d="M8 2v3M16 2v3M21 8.5V17c0 3-1.5 5-5 5H8c-3.5 0-5-2-5-5V8.5c0-3 1.5-5 5-5h8c3.5 0 5 2 5 5Z"/><path stroke="#292D32" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5" d="M8 11h8M8 16h4" opacity=".4"/></svg>
        <span>{{ $ownPrivateCount }}</span>
        <span>{{ __('Own private notes') }},</span>
    </span>
    {{-- own pubic notes --}}
    <span class="flex items-center space-x-0.5">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"><path stroke="#292D32" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5" d="M8 2v3M16 2v3M21 8.5V17c0 3-1.5 5-5 5H8c-3.5 0-5-2-5-5V8.5c0-3 1.5-5 5-5h8c3.5 0 5 2 5 5Z"/><path stroke="#292D32" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5" d="M8 11h8M8 16h4" opacity=".4"/></svg>
        <span>{{ $ownPublicCount }}</span>
        <span>{{ __('Own public notes') }}</span>
    </span>
</div>

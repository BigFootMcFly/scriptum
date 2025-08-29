<div>
    <!-- Search / Filters -->
    <div class="fi-input-wrp">
        <div class="fi-input-wrp-prefix fi-input-wrp-prefix-has-content fi-inline">
            <svg wire:loading.remove.delay.default="1" wire:target="search" class="fi-icon fi-size-md" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z" clip-rule="evenodd"></path>
            </svg>
            <svg fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="fi-icon fi-loading-indicator fi-size-md" wire:loading.delay.default="" wire:target="search">
                <path clip-rule="evenodd" d="M12 19C15.866 19 19 15.866 19 12C19 8.13401 15.866 5 12 5C8.13401 5 5 8.13401 5 12C5 15.866 8.13401 19 12 19ZM12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" fill-rule="evenodd" fill="currentColor" opacity="0.2"></path>
                <path d="M2 12C2 6.47715 6.47715 2 12 2V5C8.13401 5 5 8.13401 5 12H2Z" fill="currentColor"></path>
            </svg>
        </div>
        <div class="fi-input-wrp-content-ctn">
            <input
                autocomplete="off"
                maxlength="1000"
                placeholder="Search"
                type="search"
                wire:key="global-search.field.input"
                x-bind:id="$id('input')"
                x-on:keydown.down.prevent.stop="$dispatch('focus-first-global-search-result')"
                wire:model.live.debounce.300ms="search"
                x-mousetrap.global.="document.getElementById($id('input')).focus()"
                class="fi-input fi-input-has-inline-prefix"
                id="search">
        </div>

    </div>

    @if(auth()->check())
        <x-filament::button
            color="primary"
            class="mt-4"
            wire:click="openCreateModal()"
        >
            New Note
        </x-filament::button>
    @endif

</div>
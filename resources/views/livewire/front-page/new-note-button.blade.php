<div>
    @script
        <script>
            document.addEventListener('livewire:navigated', (event) => {
                let url = new URL(event.target.URL);
                $dispatch('refresh-new-note-button', [url.pathname]);
                $dispatch('update-vieving-mode');
            })
        </script>
    @endscript

    @if(auth()->check())
    <button
        x-data
        class="create-note"
        wire:click="$dispatch('create-new-note')"
        @keydown.window.ctrl.shift.n.prevent="$dispatch('create-new-note')"
        x-tooltip.raw="Add new Note"
    >+</button>
    @else
    {{-- NOTE: this is here to compensate the space the Button would take up --}}
        <div class="w-9 h-9 ml-3 -mr-2 inline-block"></div>
    @endif
</div>

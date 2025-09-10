@use('App\Enums\FrontPageViewingMode')
<div>

{{--    @persist('viewing-mode-button') --}}
    <button class="toggle-view-mode view-mode-{{ $viewingMode->value }}"
    {{--    wire:click="toggleViewingMode" --}}
    @click="$dispatch('toggle-viewing-mode', { event: serializePointerEvent($event)})"
    >
        <span>
            {{ $viewingMode->name }}
        </span>
    </button>
{{--    @endpersist --}}
</div>

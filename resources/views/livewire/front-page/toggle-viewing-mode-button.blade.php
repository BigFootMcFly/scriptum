@use('App\Enums\FrontPageViewingMode')
<div>
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}
    <button @class([
        "toggle-view-mode",
        "view-mode-private" => $viewingMode === FrontPageViewingMode::Private,
        "view-mode-public" => $viewingMode === FrontPageViewingMode::Public,
        "view-mode-admin" => $viewingMode === FrontPageViewingMode::Admin,
        "view-mode-guest" => $viewingMode === FrontPageViewingMode::Guest,
    ])
{{--    wire:click="toggleViewingMode" --}}
    @click="$dispatch('toggle-viewing-mode', { event: serializePointerEvent($event)})"

    >
    <span>
        {{ $viewingMode->name }}
    </span>

    </button>
</div>

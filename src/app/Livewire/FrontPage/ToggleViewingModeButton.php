<?php

namespace App\Livewire\FrontPage;

use App\Enums\FrontPageViewingMode;
use Livewire\Attributes\On;
use Livewire\Component;

class ToggleViewingModeButton extends Component
{
    // public static bool $persist = false;

    public $viewingMode = FrontPageViewingMode::Guest;

    public function booted(): void
    {
        $this->initializeViewingMode();
    }

    /**
     * Sets the Viewing Mode based on session and user values
     * NOTE: Admin mode is only stored temporary in the session, not in the database
     */
    protected function initializeViewingMode(): void
    {
        // guest is always guest
        if (! auth()->check()) {
            $this->viewingMode = FrontPageViewingMode::Guest;

            return;
        }

        $sessionViewingMode = session()->get('user.viewing_mode', null);

        // check for admin mode
        if ($sessionViewingMode === FrontPageViewingMode::Admin && auth()->user()->isAdmin()) {
            $this->viewingMode = FrontPageViewingMode::Admin;

            return;
        }

        // failsafe
        if ($sessionViewingMode !== null) {
            // TODO: add logging here, this should not happen
            session()->forget('user.viewing_mode');
        }

        // hydrate view mode from the user
        $this->viewingMode = auth()->user()->viewing_mode;

    }

    #[On('refresh-viewing-mode-button')]
    public function onRefreshComponent() {}

    /**
     * Saves the current ViewingMode into the database
     */
    protected function saveCurrentState(): void
    {
        // failsave
        if (! auth()->check()) {
            // TODO: add logging here, this should not happen
            return;
        }

        // skip if not changed
        if (auth()->user()->viewing_mode === $this->viewingMode) {
            return;
        }

        // save the new value
        auth()->user()->update(['viewing_mode' => $this->viewingMode]);
    }

    /**
     * The handler for the browser ViewMode show/change button
     */
    #[On('toggle-viewing-mode')]
    public function toggleViewingMode(array $event): void
    {
        // guests cannot change viewing mode
        if (! auth()->check()) {
            // TODO: maybe send a notification to the guest, that this is only available to registered users... - or not
            $this->viewingMode = FrontPageViewingMode::Guest;

            return;
        }

        $adminModeRequested = $this->isRequestingForAdminMode($event);

        // handle admin mode request
        if ($adminModeRequested && auth()->user()->isAdmin()) {
            $this->viewingMode = FrontPageViewingMode::Admin;
            // session(['user.viewing_mode' => FrontPageViewingMode::Admin]);
            session()->put('user.viewing_mode', FrontPageViewingMode::Admin);
            $this->dispatchUpdateRequests();

            return;
        }

        // forget admin mode
        session()->forget('user.viewing_mode');

        // deny admin mode request for non-admin users
        if ($adminModeRequested) {
            // TODO: add logging here
            // NOTE: if this was aa accidental bad click or  ahacking attempt, we simple treat it as a normal change mode request
        }

        // failsafe
        if ($this->viewingMode === FrontPageViewingMode::Guest) {
            $this->viewingMode = auth()->user()->viewing_mode;
            // TODO: add logging here, this should not happen
        }

        $this->viewingMode = match ($this->viewingMode) {
            FrontPageViewingMode::Private => FrontPageViewingMode::Public,
            FrontPageViewingMode::Public => FrontPageViewingMode::Private,
            FrontPageViewingMode::Admin => auth()->user()->viewing_mode,
            default => FrontPageViewingMode::Private, // TODO: add error handling/logging here, this should not happen
        };
        $this->saveCurrentState();
        $this->dispatchUpdateRequests();
    }

    /**
     * Dispathes update requests to other component(s)
     *
     * @param  bool  $noteList  - requesst for \App\Filament\User\Pages\FrontPage
     * @param  bool  $topBar  - requesst for \App\Livewire\FrontPage\TopBar
     */
    protected function dispatchUpdateRequests(bool $noteList = true, bool $topBar = true): void
    {
        if ($noteList) {
            $this->dispatch('refresh-note-list');
        }
        if ($topBar) {
            $this->dispatch('refresh-topbar');
        }
    }

    /**
     * Check, if te user did request for admin mode
     * NOTE: admin mode can be requested by admin users by pressing CTRL+ALT+SHIT+LeftClick
     */
    protected function isRequestingForAdminMode(array $event): bool
    {
        return $event['ctrl'] && $event['alt'] && $event['shift'];
    }

    public function render()
    {
        return view('livewire.front-page.toggle-viewing-mode-button');
    }
}

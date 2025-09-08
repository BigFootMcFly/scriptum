<?php

namespace App\Livewire\FrontPage;

use App\Enums\FrontPageViewingMode;
use Livewire\Attributes\On;
use Livewire\Component;

class ToggleViewingModeButton extends Component
{
    public FrontPageViewingMode $viewingMode = FrontPageViewingMode::Guest;

    public function mount(): void
    {

    }

    #[On('update-vieving-mode')]
    public function updateViewingMode(): void
    {
        dump("UPDATED");
        if (auth()->check()) {
            $this->viewingMode = FrontPageViewingMode::Private;
            return;
        }

        $this->viewingMode = FrontPageViewingMode::Guest;
        return;
        //TODO: do this
        /*
            if current is guest, and auth()-->check(), get from the database (user.settings) or defaults to public,
            or maybe check the session...
        */
    }
#
    #[On('toggle-viewing-mode')]
    public function toggleViewingMode(array $event): void
    {
        $newMode = $this->requestedMode($event);

        dump($newMode);

        if (false === $newMode) {
            //TODO: maybe add logging here...
            return;
        }
        $this->viewingMode = $newMode;
    }

    protected function requestedMode(array $event): FrontPageViewingMode|bool
    {
        // guest users are public only
        if (!auth()->check()) {
            return FrontPageViewingMode::Guest;
            //return false;
        }

        // if asking for admin mode
        if ($event['ctrl'] && $event['alt'] && $event['shift']) {
            // if user is admin
            if (auth()->user()->isAdmin()) {
                return FrontPageViewingMode::Admin;
            }
            // if user is not admin
            return false;
        }

        return match($this->viewingMode) {
            FrontPageViewingMode::Private => FrontPageViewingMode::Public,
            FrontPageViewingMode::Public => FrontPageViewingMode::Private,
            default => false, //something went wrong, this should not happenenig, //TODO: add error handling here
        };

    }

    public function render()
    {
        return view('livewire.front-page.toggle-viewing-mode-button');
    }
}

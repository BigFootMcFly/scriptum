<?php

namespace App\Livewire\FrontPage;

use App\Enums\FrontPageViewingMode;
use Livewire\Attributes\On;
use Livewire\Component;

use function Symfony\Component\String\b;

class ToggleViewingModeButton extends Component
{
    protected $_viewingMode; // = FrontPageViewingMode::Guest;

    protected bool $_update_mode = false;

    public FrontPageViewingMode $viewingMode {
        get {
            return $this->_viewingMode ?? $this->getSavedViewMode();
        }
        set(FrontPageViewingMode $value) {
            if (true !== $this->_update_mode) {
                //dump('UNWANTED WRITE DETECTED !!!');
                return;
            }
            $this->_viewingMode = $value;
            if (auth()->check() && $value !== FrontPageViewingMode::Admin) {
                $this->saveCurrentState();
            }
            $this->dispatch('viewing-mode-updated');
            $this->_update_mode = false;
        }
    }

    protected function saveCurrentState(): void
    {
        if (!auth()->check()) {
            return;
        }

        auth()->user()->refresh();
        if (auth()->user()->viewing_mode === $this->viewingMode) {
            return;
        }

        auth()->user()->update(['viewing_mode' => $this->viewingMode]);
        auth()->user()->refresh();

    }

    public function mount(): void
    {
        $this->updateViewingMode();
    }

    public function booted(): void
    {
        //dump($this->_viewingMode);
        $this->_viewingMode = $this->getSavedViewMode();
        //dump($this->_viewingMode);

        //$this->updateViewingMode();
    }


    #[On('spa-navigation')]
    public function onSpaNavigation(string $pathName): void {
        // add changees here if needed
    }

    #[On('update-vieving-mode')]
    public function updateViewingMode(): void
    {

        // guest is always guest
        if (!auth()->check()) {
            $this->_viewingMode = FrontPageViewingMode::Guest;
            return;
        }

        $sessionViewingMode = session('user.viewing_mode', null);

        if ($sessionViewingMode == FrontPageViewingMode::Admin && auth()->user()->isAdmin()) {
            $this->_viewingMode = FrontPageViewingMode::Admin;
            return;
        }

        auth()->user()->refresh();
        $this->_viewingMode = auth()->user()->viewing_mode;
        return;

    }

    protected function getSavedViewMode(): FrontPageViewingMode
    {
        if (!auth()->check()) {
            return FrontPageViewingMode::Guest;
        }

        //auth()->user()->refresh();
        return auth()->user()->viewing_mode;
    }
#
    #[On('toggle-viewing-mode')]
    public function toggleViewingMode(array $event): void
    {
        // guests cannot change viewing mode
        if (!auth()->check()) {
            //TODO: a a notification to the guest
            $this->_viewingMode = FrontPageViewingMode::Guest;
            return;
        }

        $requestedAdminMode = $this->isAdminModerequest($event);

        // handle admin mode request
        if ($requestedAdminMode && auth()->user()->isAdmin()) {
            $this->_viewingMode = FrontPageViewingMode::Admin;
            session(['user.viewing_mode' => FrontPageViewingMode::Admin]);
            return;
        }

        // deny admin mode request for non-admin users
        if ($requestedAdminMode) {
            //TODO: ad logging here
            //NOTE: if this was aa accidental bad click or  ahacking attempt, we simple treat it as a normal change mode request
        }

        // failsafe for livewire rehydrate call
        if ($this->viewingMode === FrontPageViewingMode::Guest) {
            $this->_viewingMode = $this->getSavedViewMode();
        }

        $this->_update_mode = true;
        $x = match($this->viewingMode) {
            //FrontPageViewingMode::Admin => auth()->user()->viewing_mode,
            FrontPageViewingMode::Private => FrontPageViewingMode::Public,
            FrontPageViewingMode::Public => FrontPageViewingMode::Private,
            //default => false, //something went wrong, this should not happenenig, //TODO: add error handling/logging here
        };
        $this->viewingMode =  match($this->viewingMode) {
            //FrontPageViewingMode::Admin => auth()->user()->viewing_mode,
            FrontPageViewingMode::Private => FrontPageViewingMode::Public,
            FrontPageViewingMode::Public => FrontPageViewingMode::Private,
            //default => false, //something went wrong, this should not happenenig, //TODO: add error handling/logging here
        };
    }

    protected function isAdminModerequest(array $event): bool
    {
        return $event['ctrl'] && $event['alt'] && $event['shift'];
    }

    public function render()
    {
        return view('livewire.front-page.toggle-viewing-mode-button');
    }
}

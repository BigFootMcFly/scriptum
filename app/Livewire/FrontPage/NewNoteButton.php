<?php

namespace App\Livewire\FrontPage;

use App\Filament\User\Pages\FrontPage;
use App\Filament\User\Resources\Notes\Pages\ListNotes;
use Livewire\Attributes\On;
use Livewire\Component;

class NewNoteButton extends Component
{

    #[On('refresh-new-note-button')]
    public function onNavigated(string $pathName) {
        /*
        //NOTE: for now, the disabled button is not used anywhere, this functionality will propably be removed later, if teher could no new use for it be found
        //      the event may be remaining here, in case a refres is needed, but could be removed as well...
        $frontPagePath =  parse_url(FrontPage::getNavigationUrl(),  PHP_URL_PATH);
        $manageMyNotesPath = parse_url(ListNotes::getNavigationUrl(),  PHP_URL_PATH);
        //$this->disabled = $manageMyNotesPath === $pathName;
        $this->disabled = false;
        */
    }

    public function render()
    {
        return view('livewire.front-page.new-note-button');
    }
}

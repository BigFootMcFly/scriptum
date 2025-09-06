<?php

namespace App\Livewire\FrontPage;

use App\Filament\User\Pages\FrontPage;
use App\Filament\User\Resources\Notes\Pages\ListNotes;
use Livewire\Attributes\On;
use Livewire\Component;

class NewNoteButton extends Component
{

    public bool $disabled = false;


    #[On('refresh-new-note-button')]
    public function onNavigated(string $pathName) {
        $frontPagePath =  parse_url(FrontPage::getNavigationUrl(),  PHP_URL_PATH);
        $manageMyNotesPath = parse_url(ListNotes::getNavigationUrl(),  PHP_URL_PATH);

        // disabée on the notes for now, that is a button for this over the list.
        //TODO: maybe later remove that button and use the main button and update the table after a new record is added.
        $this->disabled = $manageMyNotesPath === $pathName;
    }

    public function render()
    {
        return view('livewire.front-page.new-note-button');
    }
}

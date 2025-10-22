<?php

namespace App\Livewire\FrontPage;

use Livewire\Component;

class SearchBox extends Component
{
    public string $search = '';

    public function mount()
    {
        $this->search = session()->get('front-page-search', '');
    }

    public function render()
    {
        session(['front-page-search' => $this->search]);
        $this->dispatch('search-updated', search: $this->search);

        return view('livewire.front-page.search-box');
    }
}

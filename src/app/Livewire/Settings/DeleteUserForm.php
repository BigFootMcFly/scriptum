<?php

namespace App\Livewire\Settings;

use App\Livewire\Actions\Logout;
use App\Models\User;
use Livewire\Component;

class DeleteUserForm extends Component
{
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(User::assure(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}

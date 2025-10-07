<?php

namespace App\Livewire\Settings;

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DeleteUserForm extends Component
{
    public string $password = '';

    public bool $confirmingUserDeletion = false;

    public function confirmUserDeletion(): void
    {
        $this->confirmingUserDeletion = true;
    }

    public function cancelUserDeletion(): void
    {
        $this->confirmingUserDeletion = false;
        $this->password = '';
    }

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}

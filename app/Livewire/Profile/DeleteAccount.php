<?php

namespace App\Livewire\Profile;

use App\Livewire\Actions\Logout;
use App\Traits\Livewire\WithRateLimiting;
use App\Traits\Livewire\WithToast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class DeleteAccount extends Component
{
    use WithToast, WithRateLimiting;

    public string $password = '';

    public function messages(): array
    {
        return [
            'password.required' => 'لازم تكتب كلمة السر للتأكيد',
            'password.current_password' => 'كلمة السر غلط',
        ];
    }

    public function deleteUser(Logout $logout): void
    {
        if ($this->isRateLimited('delete-account', 3)) {
            return;
        }

        try {
            $this->validate([
                'password' => ['required', 'string', 'current_password'],
            ]);
        } catch (ValidationException $e) {
            $this->toastError(collect($e->errors())->flatten()->first());
            throw $e;
        }

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }

    public function render()
    {
        return view('livewire.profile.delete-account');
    }
}

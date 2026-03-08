<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\WorkoutSession;
use App\Enums\SessionStatus;
use Livewire\Attributes\On;
use Livewire\Component;

class PublicTrustProfile extends Component
{
    public bool $showModal = false;
    public ?User $targetUser = null;
    public int $completedSessionsCount = 0;

    #[On('openTrustProfile')]
    public function loadProfile($userId): void
    {
        $this->targetUser = User::find($userId);

        if ($this->targetUser) {
            $this->completedSessionsCount = WorkoutSession::where(function ($query) {
                $query->where('user_a_id', $this->targetUser->id)
                    ->orWhere('user_b_id', $this->targetUser->id);
            })->where('status', SessionStatus::Completed)->count();

            $this->showModal = true;
            $this->dispatch('hide-bottom-nav');
        }
    }

    public function render()
    {
        return view('livewire.public-trust-profile');
    }
}

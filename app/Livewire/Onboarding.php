<?php

namespace App\Livewire;

use App\Enums\UserLevel;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class Onboarding extends Component
{
    public string $phone = '';
    public string $gender = '';
    public $dob = '';
    public string $level = '';

    public function save()
    {
        $this->validate([
            'phone' => ['required', 'string', 'max:11', 'regex:/^(011|010|012|015)\d{8}$/'],
            'gender' => ['required', 'in:male,female'],
            'dob' => ['required', 'date', 'before:today'],
            'level' => ['required', new \Illuminate\Validation\Rules\Enum(UserLevel::class)],
        ]);

        auth()->user()->update([
            'phone' => $this->phone,
            'gender' => $this->gender,
            'dob' => $this->dob,
            'level' => $this->level,
            'is_onboarded' => true
        ]);

        return redirect()->route('home');
    }

    public function render()
    {
        return view('livewire.onboarding');
    }
}

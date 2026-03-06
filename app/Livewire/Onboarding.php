<?php

namespace App\Livewire;

use App\Enums\UserLevel;
use App\Enums\Gender;
use Illuminate\Validation\Rules\Enum;
use Livewire\Component;

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
            'gender' => ['required', new Enum(Gender::class)],
            'dob' => ['required', 'date', 'before:today'],
            'level' => ['required', new Enum(UserLevel::class)],
        ]);

        auth()->user()->update([
            'phone' => $this->phone,
            'gender' => (int) $this->gender,
            'dob' => $this->dob,
            'level' => (int) $this->level,
            'is_onboarded' => true
        ]);

        return redirect()->route('home');
    }

    public function render()
    {
        return view('livewire.onboarding');
    }
}

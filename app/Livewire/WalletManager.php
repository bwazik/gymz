<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class WalletManager extends Component
{
    public function render()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $transactions = $user
            ->glutesTransactions()
            ->latest()
            ->get();

        return view('livewire.wallet-manager', [
            'transactions' => $transactions,
            'balance' => Auth::user()->glutes_balance,
        ]);
    }
}

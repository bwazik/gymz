<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Livewire\Onboarding;
use App\Livewire\RequestsManager;
use App\Livewire\SessionManager;
use App\Livewire\WalletManager;
use App\Livewire\WorkoutFeed;
use Illuminate\Support\Facades\Route;



Route::view('offline', 'offline')->name('offline');

// Group: Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [GoogleAuthController::class, 'redirect'])->name('login');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);
});

Route::post('logout', [GoogleAuthController::class, 'logout'])->name('logout')->middleware('auth');

// Group: Onboarding (Requires Auth, but stops if already onboarded)
Route::get('/onboarding', Onboarding::class)->name('onboarding')->middleware('auth');

// Public/Feed (Guests can view, authenticated users must be onboarded)
Route::get('/', WorkoutFeed::class)->name('home')->middleware('onboarded');

// Group: Authenticated & Onboarded Users
Route::middleware(['auth', 'onboarded'])->group(function () {
    Route::view('profile', 'profile')->name('profile');
    Route::get('wallet', WalletManager::class)->name('wallet');
    Route::get('requests', RequestsManager::class)->name('requests');
    Route::get('sessions', SessionManager::class)->name('sessions');
});

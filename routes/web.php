<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Livewire\Onboarding;
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

// Group: Authenticated & Onboarded Users
Route::middleware(['auth', 'onboarded'])->group(function () {
    Route::get('/', WorkoutFeed::class)->name('home');
    Route::view('profile', 'profile')->name('profile');
    Route::get('requests', App\Livewire\RequestsManager::class)->name('requests');
    Route::get('sessions', App\Livewire\SessionManager::class)->name('sessions');
});

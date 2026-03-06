<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'dashboard')->name('home');

Route::view('offline', 'offline')->name('offline');

Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);

require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::view('profile', 'profile')->name('profile');
    Route::get('requests', App\Livewire\RequestsManager::class)->name('requests');
    Route::get('sessions', App\Livewire\SessionManager::class)->name('sessions');
    Route::view('/onboarding', 'onboarding')->name('onboarding');
});

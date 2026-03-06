<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Onboarding;

Route::get('/', function () {
    return view('dashboard');
})->name('home')->middleware('onboarded');

Route::view('offline', 'offline')->name('offline');

Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);


Route::get('/onboarding', Onboarding::class)->name('onboarding')->middleware('auth');

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'onboarded'])->group(function () {
    Route::view('profile', 'profile')->name('profile');
    Route::get('requests', App\Livewire\RequestsManager::class)->name('requests');
    Route::get('sessions', App\Livewire\SessionManager::class)->name('sessions');
});

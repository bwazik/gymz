<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Livewire\Onboarding;
use App\Livewire\WorkoutFeed;
use Illuminate\Support\Facades\Route;

Route::get('/', WorkoutFeed::class)->name('home')->middleware('onboarded');

Route::view('offline', 'offline')->name('offline');

Route::get('/login', [GoogleAuthController::class, 'redirect'])->name('login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);
Route::post('logout', [GoogleAuthController::class, 'logout'])->name('logout');


Route::get('/onboarding', Onboarding::class)->name('onboarding')->middleware('auth');



Route::middleware(['auth', 'onboarded'])->group(function () {
    Route::view('profile', 'profile')->name('profile');
    Route::get('requests', App\Livewire\RequestsManager::class)->name('requests');
    Route::get('sessions', App\Livewire\SessionManager::class)->name('sessions');
});

<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Livewire\Onboarding;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
})->name('home')->middleware('onboarded');

Route::view('offline', 'offline')->name('offline');

Route::get('/login', [GoogleAuthController::class, 'redirect'])->name('login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);
Route::post('logout', AuthenticatedSessionController::class . '@destroy')->name('logout');


Route::get('/onboarding', Onboarding::class)->name('onboarding')->middleware('auth');

// require __DIR__ . '/auth.php';

Route::middleware(['auth', 'onboarded'])->group(function () {
    Route::view('profile', 'profile')->name('profile');
    Route::get('requests', App\Livewire\RequestsManager::class)->name('requests');
    Route::get('sessions', App\Livewire\SessionManager::class)->name('sessions');
});

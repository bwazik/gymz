<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('requests', App\Livewire\RequestsManager::class)
    ->middleware(['auth'])
    ->name('requests');

Route::get('sessions', App\Livewire\SessionManager::class)
    ->middleware(['auth'])
    ->name('sessions');

require __DIR__ . '/auth.php';

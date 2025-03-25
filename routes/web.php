<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // Rename dashboard to "feed" in UI, but kept route name for compatibility
    Route::view('feed', 'dashboard')
        ->name('dashboard');

    // Profile routes using Volt/Livewire
    Route::middleware('auth')->group(function () {
        // Profile edit route - use View directly rather than Volt::route
        Route::view('/profile/edit', 'profile.edit')
            ->name('profile.edit');
            
        // Profile view with optional username parameter
        Route::get('/profile/{username?}', function($username = null) {
            return view('profile.view', ['username' => $username]);
        })->name('profile');
    });

    Volt::route('messages', 'messages')->name('messages');

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    Route::view('/users/search', 'users.search')->name('users.search');
});

require __DIR__.'/auth.php';

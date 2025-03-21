<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // Rename dashboard to "feed" in UI, but keep route name for compatibility
    Route::view('feed', 'dashboard')
        ->name('dashboard');

    // Profile routes
    Route::get('/profile', function () {
        return view('profile');
    })->middleware(['auth'])->name('profile');

        
    Route::view('messages', 'messages')
        ->name('messages');

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});


require __DIR__.'/auth.php';

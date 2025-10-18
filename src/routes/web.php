<?php

use App\Filament\User\Pages\ViewNotePage;
use App\Filament\User\Pages\ViewUserNotesPage;
use App\Http\Controllers\Filament\EmailVerificationController;
use App\Http\Controllers\LogoutUserController;
use Filament\Auth\Pages\Login;
use Illuminate\Support\Facades\Route;

//NOTE: dashboard is in the laravel template, instead of replacing in all places, this will do the trick for now...
Route::permanentRedirect('dashboard','/');

Route::middleware('guest')->group(function () {
    Route::get("login", Login::class)->name('filament.user.auth.login');
});


//NOTE: auth is handled by filament, the starter kit routes are ignored for ow
/*
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});
*/

Route::post('logout-user', LogoutUserController::class)->name('logout-user');

Route::get('users/{user:handle}', ViewUserNotesPage::class)->name('view-user-notes');

Route::get('notes/{user}/{slug}', ViewNotePage::class)->name('view-note');

// Add email verification routes
Route::get('email-verification/verify/{id}/{hash}', EmailVerificationController::class)->name('filament.user.auth.email-verification.verify');

// disablind filament export/import routes
Route::get('filament/exports/{export}/download', fn () => abort('404'));
Route::get('filament/imports/{import}/failed-rows/download', fn () => abort(404) );

//NOTE: auth is handled by filament, the starter kit routes are ignored for ow
//require __DIR__.'/auth.php';

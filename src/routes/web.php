<?php

use App\Filament\Overwrite\EmailVerificationPrompt;
use App\Filament\User\Pages\FrontPage;
use App\Filament\User\Pages\ViewNotePage;
use App\Filament\User\Pages\ViewUserNotesPage;
use App\Helpers\TipTap\TipTapJsonContentExtractor;
use App\Http\Controllers\LogoutUserController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Models\Note;
use App\Models\User;
use Filament\Auth\Http\Controllers\EmailVerificationController;
use Filament\Auth\Pages\Login;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

/*
Route::get('/', function () {
    return redirect('user/front-page');
    //return view('welcome');
})->name('home');
*/

/*
//TODO: remove this
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
*/

Route::middleware('guest')->group(function () {
    Route::get("login", Login::class)->name('filament.user.auth.login');
});

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

//require __DIR__.'/auth.php';

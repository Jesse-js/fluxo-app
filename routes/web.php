<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', fn() => redirect()->route('login'));

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');
});

Route::post('/auth/login',    [AuthController::class, 'login'])->name('auth.login');
Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/auth/logout',   [AuthController::class, 'logout'])->name('auth.logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // ── Perfil ─────────────────────────────────────────────────────────
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/',               [ProfileController::class, 'edit'])->name('edit');
        Route::post('/update',         [ProfileController::class, 'update'])->name('update');
        Route::post('/avatar',         [ProfileController::class, 'updateAvatar'])->name('avatar');
        Route::post('/avatar/remove',  [ProfileController::class, 'removeAvatar'])->name('avatar.remove');
        Route::post('/password',       [ProfileController::class, 'updatePassword'])->name('password');
        Route::post('/destroy',        [ProfileController::class, 'destroy'])->name('destroy');
    });
});

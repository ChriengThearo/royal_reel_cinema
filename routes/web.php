<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\Route;

// ── Public routes ─────────────────────────────────────────────────────────────

// Homepage — publicly accessible, no auth required
Route::get('/', [MovieController::class, 'index'])->name('home');

// Login / Register page (guests only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});

// Logout (authenticated users only)
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ── Protected routes ──────────────────────────────────────────────────────────

Route::middleware('auth')->group(function () {
    // Movie detail / player page
    Route::get('/movies/{slug}', [MovieController::class, 'show'])->name('movies.show');

    // Signed video stream URL (called via JS fetch, returns JSON)
    Route::get('/movies/{slug}/stream-url', [MovieController::class, 'streamUrl'])->name('movies.stream-url');
});

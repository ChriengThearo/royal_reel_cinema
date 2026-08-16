<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\Route;

// ── Public routes ─────────────────────────────────────────────────────────────

Route::get('/', [MovieController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ── Protected movie routes ────────────────────────────────────────────────────

Route::middleware('auth')->group(function () {
    Route::get('/movies/{slug}', [MovieController::class, 'show'])->name('movies.show');
    Route::get('/movies/{slug}/stream-url', [MovieController::class, 'streamUrl'])->name('movies.stream-url');
});

// ── Admin routes ──────────────────────────────────────────────────────────────

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('movies', Admin\MovieController::class);
    Route::delete('movies/{movie}/videos/{asset}', [Admin\MovieController::class, 'destroyVideo'])
        ->name('movies.videos.destroy');

    Route::resource('genres', Admin\GenreController::class);
    Route::resource('plans', Admin\PlanController::class);

    Route::get('users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::get('users/{user}', [Admin\UserController::class, 'show'])->name('users.show');
    Route::patch('users/{user}/role', [Admin\UserController::class, 'updateRole'])->name('users.update-role');

    Route::get('subscriptions', [Admin\SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::patch('subscriptions/{subscription}/cancel', [Admin\SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');

    Route::get('payments', [Admin\PaymentController::class, 'index'])->name('payments.index');
});

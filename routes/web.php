<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\MatchController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/standings', [PublicController::class, 'standings'])->name('standings');
Route::get('/sport/{slug}', [PublicController::class, 'sport'])->name('sport.show');

// Admin routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('teams', TeamController::class)->except(['show']);

    Route::resource('matches', MatchController::class)->except(['show']);

    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::patch('/groups/{group}', [GroupController::class, 'update'])->name('groups.update');
    Route::delete('/groups/{group}', [GroupController::class, 'destroy'])->name('groups.destroy');

    Route::get('/results', [ResultController::class, 'index'])->name('results.index');
    Route::get('/results/{sport}', [ResultController::class, 'edit'])->name('results.edit');
    Route::post('/results/{sport}', [ResultController::class, 'update'])->name('results.update');

    Route::resource('users', UserController::class)->except(['show']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth controllers use route('dashboard') — redirect to admin
Route::get('/dashboard', fn() => redirect()->route('admin.dashboard'))->middleware('auth')->name('dashboard');

require __DIR__.'/auth.php';

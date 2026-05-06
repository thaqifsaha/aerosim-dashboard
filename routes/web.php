<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PilotSelectionController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified', 'nocache'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/pilots/{id}', [DashboardController::class, 'pilotProfile'])
        ->name('pilots.show');

    Route::get('/flight-sessions/{id}', [DashboardController::class, 'show'])
        ->name('flight-sessions.show');

    Route::get('/flight-sessions/{id}/report', [DashboardController::class, 'report'])
        ->name('flight-sessions.report');

    Route::patch('/flight-sessions/{id}/aircraft', [DashboardController::class, 'updateAircraft'])
        ->name('flight-sessions.update-aircraft');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::patch('/profile/photo', [ProfileController::class, 'updatePhoto'])
        ->name('profile.photo.update');

    Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])
        ->name('profile.photo.delete');

    Route::get('/pilot-selection', [PilotSelectionController::class, 'index'])
        ->name('pilot-selection.index');

    Route::post('/pilot-selection', [PilotSelectionController::class, 'update'])
        ->name('pilot-selection.update');
});

require __DIR__.'/auth.php';
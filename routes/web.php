<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PilotSelectionController;
use App\Http\Controllers\FlightScheduleController;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['auth', 'verified', 'nocache'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::view('/contact-about', 'contact-about')
        ->name('contact-about');

    Route::get('/pilots/{id}', [DashboardController::class, 'pilotProfile'])
        ->name('pilots.show');

    Route::delete('/pilots/{pilot}/deactivate', [DashboardController::class, 'deactivatePilot'])
        ->name('pilots.deactivate');

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

    Route::post('/notifications/flight-sessions/read', [NotificationController::class, 'markFlightSessionsAsRead'])
        ->name('notifications.flight-sessions.read');

    Route::get('/pilot-selection', [PilotSelectionController::class, 'index'])
        ->name('pilot-selection.index');

    Route::post('/pilot-selection', [PilotSelectionController::class, 'update'])
        ->name('pilot-selection.update');

    Route::resource('flight-schedules', FlightScheduleController::class)
        ->except(['create', 'show']);

    Route::patch('/flight-schedules/{flight_schedule}/cancel', [FlightScheduleController::class, 'cancel'])
        ->name('flight-schedules.cancel');
});

require __DIR__.'/auth.php';

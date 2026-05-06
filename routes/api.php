<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FlightSessionController;
use App\Http\Controllers\TelemetryController;
use App\Http\Controllers\Api\FlightDataController;

Route::get('/flight-sessions', [FlightSessionController::class, 'index']);
Route::get('/flight-sessions/{id}', [FlightSessionController::class, 'show']);
Route::post('/telemetry', [TelemetryController::class, 'store']);
Route::post('/flight-sessions/start', [FlightSessionController::class, 'start']);
Route::post('/flight-sessions/end/{id}', [FlightSessionController::class, 'end']);
Route::post('/flight-data', [FlightDataController::class, 'store']);
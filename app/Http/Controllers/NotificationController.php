<?php

namespace App\Http\Controllers;

use App\Notifications\FlightSessionCompletedNotification;
use App\Notifications\FlightScheduleCancelledNotification;
use App\Notifications\FlightScheduleBookedNotification;
use App\Notifications\FlightScheduleReminderNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NotificationController extends Controller
{
    public function markFlightSessionsAsRead(Request $request): Response|RedirectResponse
    {
        $request->user()
            ->unreadNotifications()
            ->whereIn('type', [
                FlightSessionCompletedNotification::class,
                FlightScheduleBookedNotification::class,
                FlightScheduleReminderNotification::class,
                FlightScheduleCancelledNotification::class,
            ])
            ->update(['read_at' => now()]);

        if ($request->expectsJson()) {
            return response()->noContent();
        }

        return back();
    }
}

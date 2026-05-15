<?php

namespace App\Http\Controllers;

use App\Notifications\FlightSessionCompletedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NotificationController extends Controller
{
    public function markFlightSessionsAsRead(Request $request): Response|RedirectResponse
    {
        $request->user()
            ->unreadNotifications()
            ->where('type', FlightSessionCompletedNotification::class)
            ->update(['read_at' => now()]);

        if ($request->expectsJson()) {
            return response()->noContent();
        }

        return back();
    }
}

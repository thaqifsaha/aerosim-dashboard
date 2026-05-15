<?php

namespace App\Providers;

use App\Notifications\FlightSessionCompletedNotification;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        View::composer('layouts.navigation', function ($view) {
            $user = auth()->user();
            $notifications = collect();
            $unreadNotificationCount = 0;

            if ($user && Schema::hasTable('notifications')) {
                $notificationQuery = $user->notifications()
                    ->where('type', FlightSessionCompletedNotification::class);

                $notifications = (clone $notificationQuery)
                    ->latest()
                    ->take(8)
                    ->get();

                $unreadNotificationCount = (clone $notificationQuery)
                    ->whereNull('read_at')
                    ->count();
            }

            $view->with([
                'flightSessionNotifications' => $notifications,
                'unreadFlightSessionNotificationCount' => $unreadNotificationCount,
            ]);
        });
    }
}

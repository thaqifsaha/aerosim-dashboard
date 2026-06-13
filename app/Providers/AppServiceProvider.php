<?php

namespace App\Providers;

use App\Notifications\FlightSessionCompletedNotification;
use App\Notifications\FlightScheduleBookedNotification;
use App\Notifications\FlightScheduleCancelledNotification;
use App\Notifications\FlightScheduleReminderNotification;
use App\Models\FlightSchedule;
use App\Mail\Transport\MailtrapApiTransport;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rules\Password;

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
        Mail::extend('mailtrap_api', function (array $config) {
            return new MailtrapApiTransport(
                apiToken: $config['api_token'] ?? '',
                inboxId: (int) ($config['inbox_id'] ?? 0),
                sandbox: filter_var($config['sandbox'] ?? true, FILTER_VALIDATE_BOOLEAN),
            );
        });

        Password::defaults(fn () => Password::min(8)->mixedCase()->symbols());

        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        View::composer('layouts.navigation', function ($view) {
            $user = auth()->user();
            $notifications = collect();
            $bookedScheduleNotifications = collect();
            $reminderNotifications = collect();
            $cancelledScheduleNotifications = collect();
            $unreadNotificationCount = 0;
            $upcomingScheduleReminders = collect();

            if ($user) {
                if (Schema::hasTable('notifications')) {
                    $notificationQuery = $user->notifications()
                        ->where('type', FlightSessionCompletedNotification::class);

                    $notifications = (clone $notificationQuery)
                        ->latest()
                        ->take(8)
                        ->get();

                    $cancelledScheduleNotificationQuery = $user->notifications()
                        ->where('type', FlightScheduleCancelledNotification::class);

                    $bookedScheduleNotificationQuery = $user->notifications()
                        ->where('type', FlightScheduleBookedNotification::class);

                    $reminderNotificationQuery = $user->notifications()
                        ->where('type', FlightScheduleReminderNotification::class);

                    $bookedScheduleNotifications = (clone $bookedScheduleNotificationQuery)
                        ->whereNull('read_at')
                        ->latest()
                        ->take(8)
                        ->get();

                    $cancelledScheduleNotifications = (clone $cancelledScheduleNotificationQuery)
                        ->where('created_at', '>=', now()->subDays(7))
                        ->latest()
                        ->take(8)
                        ->get();

                    $reminderNotifications = (clone $reminderNotificationQuery)
                        ->whereNull('read_at')
                        ->latest()
                        ->take(8)
                        ->get();

                    $unreadNotificationCount = (clone $notificationQuery)
                        ->whereNull('read_at')
                        ->count()
                        + (clone $bookedScheduleNotificationQuery)
                            ->whereNull('read_at')
                            ->count()
                        + (clone $reminderNotificationQuery)
                            ->whereNull('read_at')
                            ->count()
                        + (clone $cancelledScheduleNotificationQuery)
                            ->whereNull('read_at')
                            ->count();
                }

                if (Schema::hasTable('flight_schedules')) {
                    $upcomingScheduleReminders = FlightSchedule::with('user')
                        ->upcoming()
                        ->when(
                            $user->role !== 'admin',
                            fn ($query) => $query->where('user_id', $user->id)
                        )
                        ->orderBy('scheduled_date')
                        ->orderBy('scheduled_time')
                        ->take(8)
                        ->get();
                }
            }

            $view->with([
                'flightSessionNotifications' => $notifications,
                'bookedScheduleNotifications' => $bookedScheduleNotifications,
                'reminderNotifications' => $reminderNotifications,
                'cancelledScheduleNotifications' => $cancelledScheduleNotifications,
                'unreadFlightSessionNotificationCount' => $unreadNotificationCount,
                'upcomingScheduleReminders' => $upcomingScheduleReminders,
            ]);
        });
    }
}

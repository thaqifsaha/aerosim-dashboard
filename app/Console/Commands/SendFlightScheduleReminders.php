<?php

namespace App\Console\Commands;

use App\Models\FlightSchedule;
use App\Models\User;
use App\Notifications\FlightScheduleReminderNotification;
use Illuminate\Console\Command;

class SendFlightScheduleReminders extends Command
{
    protected $signature = 'flight-schedules:send-reminders';

    protected $description = 'Send one-day-before reminders for upcoming flight schedules';

    public function handle(): int
    {
        $schedules = FlightSchedule::with('user')
            ->where('status', 'upcoming')
            ->whereDate('scheduled_date', today()->addDay())
            ->whereNull('reminder_sent_at')
            ->get();

        $admins = User::where('role', 'admin')->get();

        foreach ($schedules as $schedule) {
            $recipients = $admins
                ->push($schedule->user)
                ->filter()
                ->unique('id');

            $recipients->each(
                fn (User $user) => $user->notify(new FlightScheduleReminderNotification($schedule))
            );

            $schedule->update(['reminder_sent_at' => now()]);
        }

        $this->info("Sent reminders for {$schedules->count()} flight schedule(s).");

        return self::SUCCESS;
    }
}

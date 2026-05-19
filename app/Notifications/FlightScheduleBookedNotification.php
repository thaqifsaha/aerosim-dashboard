<?php

namespace App\Notifications;

use App\Models\FlightSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FlightScheduleBookedNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly FlightSchedule $flightSchedule)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $schedule = $this->flightSchedule->loadMissing('user');

        return [
            'flight_schedule_id' => $schedule->id,
            'pilot_id' => $schedule->user_id,
            'pilot_name' => $schedule->user?->name ?? 'Unknown pilot',
            'aircraft_type' => $schedule->aircraft_type,
            'scheduled_date' => optional($schedule->scheduled_date)->toDateString(),
            'scheduled_time' => $schedule->scheduled_time,
            'status' => $schedule->status,
            'booked_at' => optional($schedule->created_at)->toDateTimeString(),
        ];
    }
}

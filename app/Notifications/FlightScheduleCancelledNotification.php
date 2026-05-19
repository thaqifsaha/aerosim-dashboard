<?php

namespace App\Notifications;

use App\Models\FlightSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FlightScheduleCancelledNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly FlightSchedule $flightSchedule,
        private readonly string $cancelledByName,
    ) {
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
            'cancelled_by_name' => $this->cancelledByName,
            'cancelled_at' => now()->toDateTimeString(),
        ];
    }
}

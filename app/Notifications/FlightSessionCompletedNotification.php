<?php

namespace App\Notifications;

use App\Models\FlightSession;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FlightSessionCompletedNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly FlightSession $flightSession)
    {
    }

    /**
     * Get the notification delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $session = $this->flightSession->loadMissing(['user', 'dssResult']);

        return [
            'flight_session_id' => $session->id,
            'pilot_id' => $session->user_id,
            'pilot_name' => $session->user?->name ?? 'Unknown pilot',
            'aircraft_type' => $session->aircraft_type,
            'flight_date' => optional($session->flight_date)->toDateTimeString(),
            'duration_sec' => $session->duration_sec,
            'total_score' => $session->dssResult?->total_score,
            'pass_fail' => $session->dssResult?->pass_fail,
            'completed_at' => optional($session->end_time)->toDateTimeString(),
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightSchedule extends Model
{
    protected $fillable = [
        'user_id',
        'aircraft_type',
        'scheduled_date',
        'scheduled_time',
        'status',
        'notes',
        'related_flight_session_id',
        'reminder_sent_at',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'reminder_sent_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function relatedFlightSession()
    {
        return $this->belongsTo(FlightSession::class, 'related_flight_session_id');
    }

    public function scopeUpcoming($query)
    {
        return $query
            ->where('status', 'upcoming')
            ->whereDate('scheduled_date', '>=', today());
    }
}

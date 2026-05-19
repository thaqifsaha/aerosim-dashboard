<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightSession extends Model
{
    protected $fillable = [
        'user_id',
        'flight_date',
        'aircraft_type',
        'duration_sec',
        'start_time',
        'end_time'
    ];

    protected $casts = [
        'flight_date' => 'datetime',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function dssResult()
    {
        return $this->hasOne(DssResult::class);
    }

    public function flightData()
    {
        return $this->hasMany(FlightData::class);
    }

    public function linkedSchedule()
    {
        return $this->hasOne(FlightSchedule::class, 'related_flight_session_id');
    }

    public function getFormattedDurationAttribute(): string
    {
        return self::formatDuration($this->duration_sec);
    }

    public static function formatDuration(int|float|null $seconds): string
    {
        if ($seconds === null) {
            return '-';
        }

        $totalMinutes = intdiv((int) floor($seconds), 60);
        $hours = intdiv($totalMinutes, 60);
        $minutes = $totalMinutes % 60;

        return "{$hours}h {$minutes}m";
    }

}

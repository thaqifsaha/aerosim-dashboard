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
        return $this->belongsTo(User::class);
    }

    public function dssResult()
    {
        return $this->hasOne(DssResult::class);
    }

    public function flightData()
    {
        return $this->hasMany(FlightData::class);
    }

}

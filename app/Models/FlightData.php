<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightData extends Model
{
    protected $fillable = [
        'flight_session_id',
        'timestamp_sec',
        'elevator_deflection',
        'aileron_deflection',
        'rudder_deflection',
        'indicated_airspeed',
        'altitude',
        'vertical_speed',
        'g_force',
        'pitch',
        'roll',
    ];

    public function flightSession()
    {
        return $this->belongsTo(FlightSession::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DssResult extends Model
{
    protected $fillable = [
        'flight_session_id',
        'control_smoothness_score',
        'altitude_accuracy_score',
        'airspeed_accuracy_score',
        'safety_score',
        'excessive_g_event',
        'hard_landing_event',
        'stall_event',
        'total_score',
        'pass_fail',
        'decision_reason',
        'crash_event',
        'crash_severity',
        'unstable_flight_event',
        'overbank_event',
    ];

    public function flightSession()
    {
        return $this->belongsTo(FlightSession::class);
    }
}
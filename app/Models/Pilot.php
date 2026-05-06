<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pilot extends Model
{
    protected $fillable = [
        'pilot_code',
        'name',
        'age',
        'years_experience',
        'license_status',
        'profile_image'
    ];

    public function flightSessions()
    {
        return $this->hasMany(FlightSession::class);
    }
}

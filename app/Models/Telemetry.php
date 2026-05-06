<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Telemetry extends Model
{
    protected $fillable = [
        'ias',
        'tas',
        'pitch',
        'roll',
        'heading'
    ];
}


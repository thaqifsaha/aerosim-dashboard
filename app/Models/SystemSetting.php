<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = [
        'active_pilot_id',
    ];

    public function activePilot()
    {
        return $this->belongsTo(User::class, 'active_pilot_id');
    }
}
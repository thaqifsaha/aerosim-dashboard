<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FlightData;

class FlightDataController extends Controller
{
    public function store(Request $request)
    {
        $data = FlightData::create([
            'flight_session_id'   => $request->flight_session_id,
            'timestamp_sec'       => $request->timestamp_sec,
            'elevator_deflection' => $request->elevator_deflection,
            'aileron_deflection'  => $request->aileron_deflection,
            'rudder_deflection'   => $request->rudder_deflection,
            'indicated_airspeed'  => $request->indicated_airspeed,
            'altitude'            => $request->altitude,
            'vertical_speed'      => $request->vertical_speed,
            'g_force'             => $request->g_force,
            'pitch'               => $request->pitch,
            'roll'                => $request->roll,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Telemetry;

class TelemetryController extends Controller
{
    public function store(Request $request)
    {
        $telemetry = Telemetry::create([
            'ias' => $request->ias,
            'tas' => $request->tas,
            'pitch' => $request->pitch,
            'roll' => $request->roll,
            'heading' => $request->heading,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $telemetry
        ]);
    }
}
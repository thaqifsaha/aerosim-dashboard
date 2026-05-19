<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FlightSession;
use App\Models\DssResult;
use App\Models\SystemSetting;
use App\Models\User;
use App\Models\FlightSchedule;
use App\Notifications\FlightSessionCompletedNotification;

class FlightSessionController extends Controller
{
    public function index()
    {
        $sessions = \App\Models\FlightSession::with([
            'dssResult',
            'flightData'
        ])->latest()->get();

        return response()->json([
            'status' => 'success',
            'count' => $sessions->count(),
            'data' => $sessions
        ]);
    }

    public function show($id)
    {
        $session = \App\Models\FlightSession::with([
            'dssResult',
            'flightData'
        ])->find($id);

        if (!$session) {
            return response()->json([
                'status' => 'error',
                'message' => 'Flight session not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $session
        ]);
    }

    public function start()
    {
        $setting = SystemSetting::first();

        if (!$setting || !$setting->active_pilot_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'No active pilot selected'
            ], 400);
        }

        $session = FlightSession::create([
            'user_id' => $setting->active_pilot_id,
            'flight_date' => now(),
            'aircraft_type' => 'X-Plane 11',
            'duration_sec' => 0,
            'start_time' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'session_id' => $session->id
        ]);
    }

    private function calculateDssResult($session)
    {
        $flightData = $session->flightData;

        if ($flightData->isEmpty()) {
            return null;
        }

        $avgElevator = $flightData->avg(fn($row) => abs($row->elevator_deflection));
        $avgAileron = $flightData->avg(fn($row) => abs($row->aileron_deflection));
        $avgRudder = $flightData->avg(fn($row) => abs($row->rudder_deflection));

        $avgAltitude = $flightData->avg('altitude');
        $avgAirspeed = $flightData->avg('indicated_airspeed');

        $altitudeDeviation = $flightData->avg(fn($row) => abs($row->altitude - $avgAltitude));
        $airspeedDeviation = $flightData->avg(fn($row) => abs($row->indicated_airspeed - $avgAirspeed));

        $maxG = $flightData->max('g_force');
        $minAirspeed = $flightData->min('indicated_airspeed');
        $minVerticalSpeed = $flightData->min('vertical_speed');
        $minAltitude = $flightData->min('altitude');

        $lastRow = $flightData->sortByDesc('timestamp_sec')->first();
        $lastAltitude = $lastRow?->altitude ?? 0;
        $lastAirspeed = $lastRow?->indicated_airspeed ?? 0;
        $lastVerticalSpeed = $lastRow?->vertical_speed ?? 0;
        $lastG = $lastRow?->g_force ?? 0;

        $excessiveG = $maxG > 1.5;
        $stallEvent = $minAirspeed < 60;

        $hardLanding = $flightData->contains(function ($row) {
            return $row->altitude < 300 && $row->vertical_speed < -500;
        });

        $crashEvent = $flightData->contains(function ($row) {
            return (
                ($row->vertical_speed < -2000) || // extreme dive
                ($row->g_force > 2.5)             // strong impact spike
            );
        });

        // Ground impact detection: low altitude + high vertical speed
        $groundImpact = $flightData->contains(function ($row) {
            return $row->altitude < 100 && $row->vertical_speed < -1000;
        });

        if ($groundImpact) {
            $crashEvent = true;
        }

        if (!$crashEvent) {
            $crashEvent = (
                $lastAltitude < 800 &&
                $lastAirspeed < 100 &&
                $lastVerticalSpeed < -800
            );
        }

        $unstableFlightEvent = $flightData->contains(function ($row) {
            return abs($row->pitch) > 25 || abs($row->roll) > 35;
        });

        $overbankEvent = $flightData->contains(function ($row) {
            return abs($row->roll) > 45;
        });

        $crashSeverity = null;

        if ($crashEvent) {
            if ($minVerticalSpeed < -4000 || $maxG > 3.5) {
                $crashSeverity = 'Severe';
            } elseif ($minVerticalSpeed < -2500 || $maxG > 2.5) {
                $crashSeverity = 'Major';
            } else {
                $crashSeverity = 'Minor';
            }
        }

        // Scores out of 100
        $controlSmoothnessScore = max(0, min(100, 100 - (($avgElevator + $avgAileron + $avgRudder) * 100)));
        $altitudeAccuracyScore = max(0, min(100, 100 - ($altitudeDeviation / 10)));
        $airspeedAccuracyScore = max(0, min(100, 100 - ($airspeedDeviation * 2)));

        $safetyScore = 100;

        if ($crashEvent) {
            $safetyScore = 0;
        } else {
            if ($excessiveG) $safetyScore -= 30;
            if ($stallEvent) $safetyScore -= 40;
            if ($hardLanding) $safetyScore -= 30;
            if ($unstableFlightEvent) $safetyScore -= 20;
            if ($overbankEvent) $safetyScore -= 15;
            $safetyScore = max(0, $safetyScore);
        }

        $totalScore = round((
            $controlSmoothnessScore +
            $altitudeAccuracyScore +
            $airspeedAccuracyScore +
            $safetyScore
        ) / 4);

        $reasons = [];

        if ($excessiveG) $reasons[] = 'Excessive G-force detected';
        if ($stallEvent) $reasons[] = 'Potential stall condition detected';
        if ($hardLanding) $reasons[] = 'Hard landing condition detected';
        if ($crashEvent) $reasons[] = 'Crash event detected (' . $crashSeverity . ' severity)';
        if ($unstableFlightEvent) $reasons[] = 'Unstable aircraft attitude detected';
        if ($overbankEvent) $reasons[] = 'Overbank condition detected';

        // Override pass/fail logic
        if ($crashEvent) {
            $passFail = 'FAIL';
            $totalScore = min($totalScore, 20);
        } elseif ($hardLanding && $minVerticalSpeed < -900) {
            // severe hard landing can also force fail
            $passFail = 'FAIL';
            $totalScore = min($totalScore, 55);
        } else {
            $passFail = $totalScore >= 60 ? 'PASS' : 'FAIL';
        }

        if (empty($reasons)) {
            $reasons[] = 'Flight remained within acceptable limits';
        }

        return DssResult::updateOrCreate(
            ['flight_session_id' => $session->id],
            [
                'control_smoothness_score' => round($controlSmoothnessScore),
                'altitude_accuracy_score' => round($altitudeAccuracyScore),
                'airspeed_accuracy_score' => round($airspeedAccuracyScore),
                'safety_score' => round($safetyScore),
                'excessive_g_event' => $excessiveG,
                'hard_landing_event' => $hardLanding,
                'crash_event' => $crashEvent,
                'crash_severity' => $crashSeverity,
                'stall_event' => $stallEvent,
                'unstable_flight_event' => $unstableFlightEvent,
                'overbank_event' => $overbankEvent,
                'total_score' => $totalScore,
                'pass_fail' => $passFail,
                'decision_reason' => implode('; ', $reasons),
            ]
        );
    }

    public function end($id)
    {
        $session = FlightSession::with('flightData')->find($id);

        if (!$session) {
            return response()->json([
                'status' => 'error',
                'message' => 'Session not found'
            ], 404);
        }

        $wasAlreadyCompleted = ! is_null($session->end_time);
        $endTime = now();
        $startTime = \Carbon\Carbon::parse($session->start_time ?? $session->created_at);
        $duration = $startTime->diffInSeconds($endTime);

        $session->update([
            'end_time' => $endTime,
            'duration_sec' => $duration,
        ]);

        $dssResult = $this->calculateDssResult($session);

        if (! $wasAlreadyCompleted) {
            $session->load(['user', 'dssResult']);

            FlightSchedule::query()
                ->where('user_id', $session->user_id)
                ->where('status', 'upcoming')
                ->whereDate('scheduled_date', \Carbon\Carbon::parse($session->flight_date)->toDateString())
                ->orderBy('scheduled_time')
                ->first()
                ?->update([
                    'status' => 'completed',
                    'related_flight_session_id' => $session->id,
                ]);

            User::where('role', 'admin')
                ->get()
                ->push($session->user)
                ->filter()
                ->unique('id')
                ->each(fn (User $user) => $user->notify(new FlightSessionCompletedNotification($session)));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Session closed and DSS calculated',
            'dss_result' => $dssResult
        ]);
    }

}

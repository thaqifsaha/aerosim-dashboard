<?php

namespace App\Http\Controllers;

use App\Models\FlightSession;
use App\Models\User;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->role === 'pilot') {
            return $this->pilotProfile($user->id);
        }

        $activePilot = \App\Models\SystemSetting::with('activePilot')->first()?->activePilot;

        if ($user->role === 'admin') {
            $search = trim((string) $request->input('search', ''));

            $pilotCards = \App\Models\User::where('role', 'pilot')
                ->when($search !== '', function ($query) use ($search) {
                    $query->where(function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
                })
                ->with(['flightSessions.dssResult'])
                ->get()
                ->map(function ($pilot) {
                    $sessions = $pilot->flightSessions;
                    $sessionsWithDss = $sessions->filter(fn($session) => $session->dssResult);

                    return [
                        'id' => $pilot->id,
                        'name' => $pilot->name,
                        'email' => $pilot->email,
                        'total_sessions' => $sessions->count(),
                        'average_score' => round($sessionsWithDss->avg(fn($session) => $session->dssResult->total_score) ?? 0),
                        'pass_count' => $sessionsWithDss->filter(fn($session) => $session->dssResult->pass_fail === 'PASS')->count(),
                        'fail_count' => $sessionsWithDss->filter(fn($session) => $session->dssResult->pass_fail === 'FAIL')->count(),
                    ];
                });

            return view('dashboard', [
                'activePilot' => $activePilot,
                'pilotCards' => $pilotCards,
                'search' => $search,
                'isAdmin' => true,
            ]);
        }

        $sessions = \App\Models\FlightSession::with(['dssResult', 'user'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $sessionsWithDss = $sessions->filter(fn($session) => $session->dssResult);

        $totalSessions = $sessions->count();
        $averageScore = round($sessionsWithDss->avg(fn($session) => $session->dssResult->total_score) ?? 0);
        $passCount = $sessionsWithDss->filter(fn($session) => $session->dssResult->pass_fail === 'PASS')->count();
        $failCount = $sessionsWithDss->filter(fn($session) => $session->dssResult->pass_fail === 'FAIL')->count();

        $latestScore = $sessionsWithDss->first()?->dssResult?->total_score;
        $previousScore = $sessionsWithDss->skip(1)->first()?->dssResult?->total_score;

        $trend = null;

        if ($latestScore && $previousScore) {
            if ($latestScore > $previousScore) $trend = 'up';
            elseif ($latestScore < $previousScore) $trend = 'down';
            else $trend = 'same';
        }

        return view('dashboard', [
            'sessions' => $sessions,
            'totalSessions' => $totalSessions,
            'averageScore' => $averageScore,
            'passCount' => $passCount,
            'failCount' => $failCount,
            'trend' => $trend,
            'activePilot' => $activePilot,
            'isAdmin' => false,
        ]);
    }

    public function show($id)
    {
        $user = auth()->user();

        $query = \App\Models\FlightSession::with('dssResult');

        if ($user->role != 'admin') {
            $query->where('user_id', $user->id);
        }

        $session = $query->findOrFail($id);

        $chartData = \App\Models\FlightData::where('flight_session_id', $session->id)
            ->orderBy('timestamp_sec')
            ->get();

        $flightData = \App\Models\FlightData::where('flight_session_id', $session->id)
            ->orderBy('timestamp_sec')
            ->paginate(20);

        if (request()->ajax()) {
            return view('partials.flight-data-records', compact('flightData'))->render();
        }

        return view('flight-session-detail', [
            'session' => $session,
            'chartData' => $chartData,
            'flightData' => $flightData,
        ]);
    }

    public function pilotProfile($id)
    {
        $user = auth()->user();

        if ($user->role !== 'admin' && $user->id != $id) {
            abort(403);
        }

        $pilot = \App\Models\User::where('role', 'pilot')
            ->with(['flightSessions.dssResult'])
            ->findOrFail($id);

        $sessions = \App\Models\FlightSession::with(['dssResult', 'user'])
            ->where('user_id', $pilot->id)
            ->latest()
            ->get();

        $sessionsWithDss = $sessions->filter(fn($session) => $session->dssResult);

        $totalSessions = $sessions->count();
        $averageScore = round($sessionsWithDss->avg(fn($session) => $session->dssResult->total_score) ?? 0);
        $passCount = $sessionsWithDss->filter(fn($session) => $session->dssResult->pass_fail === 'PASS')->count();
        $failCount = $sessionsWithDss->filter(fn($session) => $session->dssResult->pass_fail === 'FAIL')->count();

        $scoreTrend = $sessions
            ->filter(fn($s) => $s->dssResult)
            ->sortBy('flight_date')
            ->values()
            ->map(function ($s) {
                $score = $s->dssResult->total_score;

                return [
                    'date' => \Carbon\Carbon::parse($s->flight_date)->format('d M'),
                    'score' => $score,
                    'is_bad' =>
                        $s->dssResult->pass_fail === 'FAIL' ||
                        $s->dssResult->crash_event ||
                        $s->dssResult->hard_landing_event ||
                        $score < 70,
                ];
            });

        $first = $scoreTrend->first()['score'] ?? 0;
        $last = $scoreTrend->last()['score'] ?? 0;

        if ($last > $first) {
            $trend = 'Improving';
        } elseif ($last < $first) {
            $trend = 'Declining';
        } else {
            $trend = 'Stable';
        }

        return view('pilot-profile', compact(
            'pilot',
            'sessions',
            'totalSessions',
            'averageScore',
            'passCount',
            'failCount',
            'scoreTrend',
            'trend'
        ));
    }

    public function deactivatePilot(User $pilot)
    {
        abort_unless(auth()->user()->role === 'admin', 403);
        abort_unless($pilot->role === 'pilot', 404);
        abort_if(auth()->id() === $pilot->id, 403);

        \App\Models\SystemSetting::where('active_pilot_id', $pilot->id)
            ->update(['active_pilot_id' => null]);

        $pilot->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Pilot account deactivated successfully.');
    }

    public function report($id)
    {
        $user = auth()->user();

        $query = \App\Models\FlightSession::with(['dssResult', 'user', 'flightData']);

        if ($user->role != 'admin') {
            $query->where('user_id', $user->id);
        }

        $session = $query->findOrFail($id);

        $pdf = Pdf::loadView('reports.flight-session-report', [
            'session' => $session,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('flight-session-' . $session->id . '-report.pdf');
    }

    public function updateAircraft(Request $request, $id)
    {
        $request->validate([
            'aircraft_type' => 'required|string|max:255',
        ]);

        $user = auth()->user();

        $query = \App\Models\FlightSession::query();

        if ($user->role != 'admin') {
            $query->where('user_id', $user->id);
        }

        $session = $query->findOrFail($id);

        $session->update([
            'aircraft_type' => $request->aircraft_type,
        ]);

        return back()->with('success', 'Aircraft updated successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\FlightSchedule;
use App\Models\User;
use App\Notifications\FlightScheduleBookedNotification;
use App\Notifications\FlightScheduleCancelledNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FlightScheduleController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $bookedDates = FlightSchedule::query()
            ->where('status', 'upcoming')
            ->whereDate('scheduled_date', '>=', today())
            ->pluck('scheduled_date')
            ->map(fn ($date) => \Carbon\Carbon::parse($date)->toDateString())
            ->values();

        if ($user->role === 'admin') {
            $upcomingSchedules = FlightSchedule::with('user')
                ->upcoming()
                ->orderBy('scheduled_date')
                ->orderBy('scheduled_time')
                ->get();

            $cancelledSchedules = FlightSchedule::with('user')
                ->where('status', 'cancelled')
                ->latest('updated_at')
                ->take(20)
                ->get();

            return view('flight-schedules.index', compact(
                'upcomingSchedules',
                'cancelledSchedules',
                'bookedDates'
            ));
        }

        $upcomingSchedules = FlightSchedule::query()
            ->where('user_id', $user->id)
            ->upcoming()
            ->orderBy('scheduled_date')
            ->orderBy('scheduled_time')
            ->get();

        return view('flight-schedules.index', compact(
            'upcomingSchedules',
            'bookedDates'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->role === 'pilot', 403);

        $validated = $this->validateSchedule($request);

        $flightSchedule = FlightSchedule::create([
            ...$validated,
            'user_id' => auth()->id(),
            'status' => 'upcoming',
        ]);

        User::where('role', 'admin')
            ->get()
            ->each(fn (User $admin) => $admin->notify(
                new FlightScheduleBookedNotification($flightSchedule)
            ));

        return back()->with('success', 'Flight session booked successfully.');
    }

    public function edit(FlightSchedule $flightSchedule): View
    {
        $this->authorizeScheduleMutation($flightSchedule);

        return view('flight-schedules.edit', compact('flightSchedule'));
    }

    public function update(Request $request, FlightSchedule $flightSchedule): RedirectResponse
    {
        $this->authorizeScheduleMutation($flightSchedule);

        $validated = $this->validateSchedule($request, $flightSchedule);

        if (auth()->user()->role === 'admin') {
            $validated['status'] = $request->validate([
                'status' => 'required|in:upcoming,cancelled',
            ])['status'];
        }

        if (
            $flightSchedule->scheduled_date->toDateString() !== $validated['scheduled_date'] ||
            substr($flightSchedule->scheduled_time, 0, 5) !== $validated['scheduled_time']
        ) {
            $validated['reminder_sent_at'] = null;
        }

        $flightSchedule->update($validated);

        return redirect()
            ->route('flight-schedules.index')
            ->with('success', 'Flight schedule updated successfully.');
    }

    public function destroy(FlightSchedule $flightSchedule): RedirectResponse
    {
        abort_unless(auth()->user()->role === 'admin', 403);
        abort_if($flightSchedule->status === 'completed', 403);

        $flightSchedule->delete();

        return back()->with('success', 'Flight schedule deleted successfully.');
    }

    public function cancel(FlightSchedule $flightSchedule): RedirectResponse
    {
        $this->authorizeScheduleMutation($flightSchedule);

        if ($flightSchedule->status !== 'cancelled') {
            $flightSchedule->update(['status' => 'cancelled']);

            if (auth()->user()->role === 'pilot') {
                $flightSchedule->loadMissing('user');

                User::where('role', 'admin')
                    ->get()
                    ->each(fn (User $admin) => $admin->notify(
                        new FlightScheduleCancelledNotification(
                            $flightSchedule,
                            auth()->user()->name,
                        )
                    ));
            }
        }

        return back()->with('success', 'Flight schedule cancelled successfully.');
    }

    private function validateSchedule(Request $request, ?FlightSchedule $ignore = null): array
    {
        return $request->validate([
            'aircraft_type' => 'required|in:Boeing 737-800,Boeing 747-400,MD-82',
            'scheduled_date' => [
                'required',
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) use ($ignore) {
                    $date = \Carbon\Carbon::parse($value);

                    if ($date->isFriday()) {
                        $fail('The selected date is unavailable because the business is closed every Friday.');
                    }

                    $alreadyBooked = FlightSchedule::query()
                        ->whereDate('scheduled_date', $date->toDateString())
                        ->where('status', 'upcoming')
                        ->when($ignore, fn ($query) => $query->whereKeyNot($ignore->id))
                        ->exists();

                    if ($alreadyBooked) {
                        $fail('The selected date is already booked.');
                    }
                },
            ],
            'scheduled_time' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    $time = \Carbon\Carbon::createFromFormat('H:i', $value);

                    if ($time->lt(\Carbon\Carbon::createFromTime(11, 0)) || $time->gt(\Carbon\Carbon::createFromTime(20, 0))) {
                        $fail('The booking time must be between 11:00 AM and 8:00 PM.');
                    }
                },
            ],
            'notes' => 'nullable|string|max:2000',
        ]);
    }

    private function authorizeScheduleMutation(FlightSchedule $flightSchedule): void
    {
        abort_if($flightSchedule->status === 'completed', 403);

        $user = auth()->user();

        if ($user->role === 'admin') {
            return;
        }

        abort_unless(
            $user->role === 'pilot' && $flightSchedule->user_id === $user->id,
            403
        );
    }
}

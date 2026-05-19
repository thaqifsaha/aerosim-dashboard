<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PilotSelectionController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $pilots = User::where('role', 'pilot')->get();
        $setting = SystemSetting::first();

        return view('pilot-selection', compact('pilots', 'setting'));
    }

    public function update(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'active_pilot_id' => [
                'nullable',
                Rule::exists('users', 'id')->where(fn ($query) => $query
                    ->where('role', 'pilot')
                    ->whereNull('deleted_at')),
            ],
        ]);

        $setting = SystemSetting::first();

        if (!$setting) {
            $setting = SystemSetting::create([
                'active_pilot_id' => $request->active_pilot_id
            ]);
        } else {
            $setting->update([
                'active_pilot_id' => $request->active_pilot_id
            ]);
        }

        return redirect()->route('pilot-selection.index')
            ->with('success', 'Active pilot updated successfully.');
    }
}

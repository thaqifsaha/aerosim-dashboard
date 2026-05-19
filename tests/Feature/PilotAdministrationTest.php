<?php

use App\Models\FlightSession;
use App\Models\SystemSetting;
use App\Models\User;

test('admin can deactivate a pilot while preserving historical flight sessions', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $pilot = User::factory()->create(['role' => 'pilot']);

    $session = FlightSession::create([
        'user_id' => $pilot->id,
        'flight_date' => now(),
        'aircraft_type' => 'Boeing 737-800',
        'duration_sec' => 0,
    ]);

    SystemSetting::create(['active_pilot_id' => $pilot->id]);

    $this->actingAs($admin)
        ->delete(route('pilots.deactivate', $pilot))
        ->assertRedirect(route('dashboard'));

    $this->assertSoftDeleted($pilot);
    expect($session->fresh()->user?->name)->toBe($pilot->name);
    expect(SystemSetting::first()->active_pilot_id)->toBeNull();
});

test('pilot cannot deactivate another pilot account', function () {
    $pilot = User::factory()->create(['role' => 'pilot']);
    $otherPilot = User::factory()->create(['role' => 'pilot']);

    $this->actingAs($pilot)
        ->delete(route('pilots.deactivate', $otherPilot))
        ->assertForbidden();
});

test('admin dashboard search filters pilots by name or email', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->create([
        'role' => 'pilot',
        'name' => 'Alpha Pilot',
        'email' => 'alpha@example.com',
    ]);
    User::factory()->create([
        'role' => 'pilot',
        'name' => 'Bravo Pilot',
        'email' => 'bravo@example.com',
    ]);

    $this->actingAs($admin)
        ->get(route('dashboard', ['search' => 'alpha']))
        ->assertOk()
        ->assertSee('Alpha Pilot')
        ->assertDontSee('Bravo Pilot');
});

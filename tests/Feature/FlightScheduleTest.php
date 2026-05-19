<?php

use App\Models\FlightSchedule;
use App\Models\User;
use App\Notifications\FlightScheduleCancelledNotification;
use App\Notifications\FlightScheduleBookedNotification;
use App\Notifications\FlightScheduleReminderNotification;
use Illuminate\Support\Facades\Notification;

test('pilot can book an available flight session date', function () {
    Notification::fake();

    $pilot = User::factory()->create(['role' => 'pilot']);
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($pilot)->post(route('flight-schedules.store'), [
        'aircraft_type' => 'Boeing 737-800',
        'scheduled_date' => now()->next('Monday')->toDateString(),
        'scheduled_time' => '11:30',
        'notes' => 'Morning simulator slot',
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('flight_schedules', [
        'user_id' => $pilot->id,
        'aircraft_type' => 'Boeing 737-800',
        'scheduled_time' => '11:30',
        'status' => 'upcoming',
    ]);

    Notification::assertSentTo($admin, FlightScheduleBookedNotification::class);
});

test('pilot cannot book a friday', function () {
    $pilot = User::factory()->create(['role' => 'pilot']);

    $response = $this->actingAs($pilot)->post(route('flight-schedules.store'), [
        'aircraft_type' => 'Boeing 737-800',
        'scheduled_date' => now()->next('Friday')->toDateString(),
        'scheduled_time' => '09:30',
    ]);

    $response->assertSessionHasErrors('scheduled_date');
    $this->assertDatabaseCount('flight_schedules', 0);
});

test('pilot cannot double book an already booked date', function () {
    $firstPilot = User::factory()->create(['role' => 'pilot']);
    $secondPilot = User::factory()->create(['role' => 'pilot']);
    $date = now()->next('Monday')->toDateString();

    FlightSchedule::create([
        'user_id' => $firstPilot->id,
        'aircraft_type' => 'Airbus A320',
        'scheduled_date' => $date,
        'scheduled_time' => '08:00',
        'status' => 'upcoming',
    ]);

    $response = $this->actingAs($secondPilot)->post(route('flight-schedules.store'), [
        'aircraft_type' => 'Boeing 737',
        'scheduled_date' => $date,
        'scheduled_time' => '09:30',
    ]);

    $response->assertSessionHasErrors('scheduled_date');
    $this->assertDatabaseCount('flight_schedules', 1);
});

test('pilot cannot book outside shop hours', function () {
    $pilot = User::factory()->create(['role' => 'pilot']);

    $response = $this->actingAs($pilot)->post(route('flight-schedules.store'), [
        'aircraft_type' => 'Boeing 737-800',
        'scheduled_date' => now()->next('Monday')->toDateString(),
        'scheduled_time' => '09:30',
    ]);

    $response->assertSessionHasErrors('scheduled_time');
    $this->assertDatabaseCount('flight_schedules', 0);
});

test('pilot cannot edit another pilots booking', function () {
    $owner = User::factory()->create(['role' => 'pilot']);
    $otherPilot = User::factory()->create(['role' => 'pilot']);

    $schedule = FlightSchedule::create([
        'user_id' => $owner->id,
        'aircraft_type' => 'Airbus A320',
        'scheduled_date' => now()->next('Monday')->toDateString(),
        'scheduled_time' => '08:00',
        'status' => 'upcoming',
    ]);

    $this->actingAs($otherPilot)
        ->get(route('flight-schedules.edit', $schedule))
        ->assertForbidden();
});

test('pilot cancellation changes status and notifies admins', function () {
    Notification::fake();

    $pilot = User::factory()->create(['role' => 'pilot']);
    $admin = User::factory()->create(['role' => 'admin']);

    $schedule = FlightSchedule::create([
        'user_id' => $pilot->id,
        'aircraft_type' => 'MD-82',
        'scheduled_date' => now()->next('Monday')->toDateString(),
        'scheduled_time' => '08:00',
        'status' => 'upcoming',
    ]);

    $this->actingAs($pilot)
        ->patch(route('flight-schedules.cancel', $schedule))
        ->assertRedirect();

    expect($schedule->fresh()->status)->toBe('cancelled');

    Notification::assertSentTo($admin, FlightScheduleCancelledNotification::class);
});

test('admin can permanently delete a cancelled booking', function () {
    $pilot = User::factory()->create(['role' => 'pilot']);
    $admin = User::factory()->create(['role' => 'admin']);

    $schedule = FlightSchedule::create([
        'user_id' => $pilot->id,
        'aircraft_type' => 'MD-82',
        'scheduled_date' => now()->next('Monday')->toDateString(),
        'scheduled_time' => '08:00',
        'status' => 'cancelled',
    ]);

    $this->actingAs($admin)
        ->delete(route('flight-schedules.destroy', $schedule))
        ->assertRedirect();

    $this->assertModelMissing($schedule);
});

test('one day reminders are sent once to the pilot and admins', function () {
    Notification::fake();

    $pilot = User::factory()->create(['role' => 'pilot']);
    $admin = User::factory()->create(['role' => 'admin']);

    $schedule = FlightSchedule::create([
        'user_id' => $pilot->id,
        'aircraft_type' => 'MD-82',
        'scheduled_date' => today()->addDay()->toDateString(),
        'scheduled_time' => '08:00',
        'status' => 'upcoming',
    ]);

    $this->artisan('flight-schedules:send-reminders')->assertSuccessful();
    $this->artisan('flight-schedules:send-reminders')->assertSuccessful();

    Notification::assertSentToTimes($pilot, FlightScheduleReminderNotification::class, 1);
    Notification::assertSentToTimes($admin, FlightScheduleReminderNotification::class, 1);
    expect($schedule->fresh()->reminder_sent_at)->not->toBeNull();
});

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flight_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('aircraft_type');
            $table->date('scheduled_date');
            $table->time('scheduled_time');
            $table->string('status')->default('upcoming');
            $table->text('notes')->nullable();
            $table->foreignId('related_flight_session_id')
                ->nullable()
                ->constrained('flight_sessions')
                ->nullOnDelete();
            $table->timestamps();

            $table->index(['scheduled_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flight_schedules');
    }
};

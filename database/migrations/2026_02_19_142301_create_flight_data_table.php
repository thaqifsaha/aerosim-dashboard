<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('flight_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_session_id')
                  ->constrained('flight_sessions')
                  ->cascadeOnDelete();

            $table->decimal('timestamp_sec', 8, 3);

            $table->float('elevator_deflection');
            $table->float('aileron_deflection');
            $table->float('rudder_deflection');

            $table->float('indicated_airspeed');
            $table->float('altitude');
            $table->float('vertical_speed');
            $table->float('g_force');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flight_data');
    }
};

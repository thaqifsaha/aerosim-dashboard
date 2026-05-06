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
        Schema::create('dss_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_session_id')
                  ->constrained('flight_sessions')
                  ->cascadeOnDelete();

            $table->integer('control_smoothness_score');
            $table->integer('altitude_accuracy_score');
            $table->integer('airspeed_accuracy_score');
            $table->integer('safety_score');

            $table->boolean('excessive_g_event')->default(false);
            $table->boolean('hard_landing_event')->default(false);
            $table->boolean('stall_event')->default(false);

            $table->integer('total_score');
            $table->enum('pass_fail', ['PASS', 'FAIL']);
            $table->string('decision_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dss_results');
    }
};

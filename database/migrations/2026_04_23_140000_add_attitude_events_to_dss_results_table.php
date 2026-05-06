<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dss_results', function (Blueprint $table) {
            $table->boolean('unstable_flight_event')->default(false)->after('stall_event');
            $table->boolean('overbank_event')->default(false)->after('unstable_flight_event');
        });
    }

    public function down(): void
    {
        Schema::table('dss_results', function (Blueprint $table) {
            $table->dropColumn(['unstable_flight_event', 'overbank_event']);
        });
    }
};

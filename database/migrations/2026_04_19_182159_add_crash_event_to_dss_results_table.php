<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dss_results', function (Blueprint $table) {
            $table->boolean('crash_event')->default(false)->after('hard_landing_event');
        });
    }

    public function down(): void
    {
        Schema::table('dss_results', function (Blueprint $table) {
            $table->dropColumn('crash_event');
        });
    }
};
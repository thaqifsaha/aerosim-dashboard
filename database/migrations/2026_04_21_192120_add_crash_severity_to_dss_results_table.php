<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('dss_results', function (Blueprint $table) {
        $table->string('crash_severity')->nullable()->after('crash_event');
    });
}

public function down(): void
{
    Schema::table('dss_results', function (Blueprint $table) {
        $table->dropColumn('crash_severity');
    });
}
};

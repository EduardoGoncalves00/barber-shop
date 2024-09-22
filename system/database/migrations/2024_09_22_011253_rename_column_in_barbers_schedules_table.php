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
        Schema::table('barbers_schedules', function (Blueprint $table) {
            $table->renameColumn('selected_date_and_time', 'selected_day_and_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barbers_schedules', function (Blueprint $table) {
            $table->renameColumn('selected_day_and_time', 'selected_date_and_time');
        });
    }
};

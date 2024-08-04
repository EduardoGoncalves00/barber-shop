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
        Schema::create('barbers_schedules', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('service_register_id');
            $table->unsignedBigInteger('customer_id')->nullable(false);
            $table->datetime('selected_date_and_time')->nullable(false);
            $table->string('observation')->nullable(true);
            $table->timestamps();

            $table->foreign('service_register_id')->references('id')->on('services_registers')->nullable(true);
            $table->foreign('customer_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barbers_schedules', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('barbers_schedules');
    }
};

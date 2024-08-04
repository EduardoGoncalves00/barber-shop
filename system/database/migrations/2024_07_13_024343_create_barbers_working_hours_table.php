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
        Schema::create('barbers_working_hours', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barber_id');
            $table->time('start_lunch')->nullable(false);
            $table->time('end_lunch')->nullable(false);
            $table->time('start_work')->nullable(false);
            $table->time('end_work')->nullable(false);

            $table->foreign('barber_id')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barbers_working_hours');
    }
};
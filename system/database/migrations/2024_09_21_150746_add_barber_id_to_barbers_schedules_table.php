<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('barbers_schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('barber_id')->nullable()->after('id');
            $table->foreign('barber_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('barbers_schedules', function (Blueprint $table) {
            $table->dropForeign(['barber_id']);
            $table->dropColumn('barber_id');
        });
    }
};

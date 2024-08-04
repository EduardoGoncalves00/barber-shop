<?php

namespace Database\Seeders;

use App\Models\BarbersWorkingHours;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserBarberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barber = User::factory()->create([
            'type' => "barber"
        ]);

        BarbersWorkingHours::create([
            'barber_id' => $barber->id,
            'start_lunch' => '12:00',
            'end_lunch' => '13:00',
            'start_work' => '09:00',
            'end_work' => '19:00',
        ]);
    }
}

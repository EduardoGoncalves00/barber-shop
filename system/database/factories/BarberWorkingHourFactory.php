<?php

namespace Database\Factories;

use App\Models\BarberWorkingHour;
use Illuminate\Database\Eloquent\Factories\Factory;

class BarberWorkingHourFactory extends Factory
{
    protected $model = BarberWorkingHour::class;

    public function definition(): array
    {
        return [
            'barber_id' => 1,
            'start_lunch' => '12:00',
            'end_lunch' => '13:00',
            'start_work' => '09:00',
            'end_work' => '19:00',
        ];
    }
}

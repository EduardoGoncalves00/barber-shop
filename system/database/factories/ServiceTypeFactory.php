<?php

namespace Database\Factories;

use App\Models\ServiceType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceTypeFactory extends Factory
{
    protected $model = ServiceType::class;

    public function definition()
    {
        return [
            'service_name' => $this->faker->randomElement(['haircut', 'beard', 'haircut and beard']),
            'value' => $this->faker->numberBetween(20, 50),
            'estimated_time' => $this->faker->randomElement(['00:30:00', '01:00:00'])
        ];
    }
}

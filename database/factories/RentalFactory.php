<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RentalFactory extends Factory
{
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-1 month', '+1 month');
        return [
            'motorcycle_id' => 1,
            'renter_id' => 2,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => fake()->dateTimeInInterval($startDate, '+5 days')->format('Y-m-d'),
            'total_price' => fake()->numberBetween(200, 800),
            'status' => fake()->randomElement(['pending', 'active', 'completed']),
            'payment_status' => fake()->randomElement(['pending', 'paid']),
        ];
    }
}
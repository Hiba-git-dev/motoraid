<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MotorcycleFactory extends Factory
{
    public function definition(): array
    {
        $brands = ['Yamaha', 'BMW', 'Honda', 'KTM', 'Ducati', 'Suzuki'];
        $models = ['Ténéré 700', 'GS 1250', 'Africa Twin', '890 Adventure', 'Multistrada', 'V-Strom 650'];
        
        $key = array_rand($brands);

        return [
            'user_id' => 1, // Sera surchargé dans le seeder
            'brand' => $brands[$key],
            'model' => $models[$key],
            'year' => fake()->numberBetween(2018, 2024),
            'plate_number' => fake()->unique()->regexify('[0-9]{4}-[A-Z]{2}-[0-9]{2}'), // Plaque marocaine stylisée
            'daily_rate' => fake()->numberBetween(40, 120),
            'deposit' => fake()->numberBetween(500, 1500),
            'description' => fake()->paragraph(),
            'image_url' => null,
            'status' => fake()->randomElement(['available', 'available', 'available', 'maintenance']),
        ];
    }
}
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    public function definition(): array
    {
        $names = ['Les Routards de l\'Atlas', 'Marrakech Desert Riders', 'Casa Moto Club', 'Rabat Night Riders', 'Désert Aventure', 'Tizi n\'Tichka Lovers'];
        return [
            'name' => fake()->unique()->randomElement($names),
            'description' => fake()->paragraph(),
            'owner_id' => 1,
            'is_private' => fake()->boolean(20),
        ];
    }
}
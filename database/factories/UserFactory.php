<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'avatar_url' => 'https://ui-avatars.com/api/?background=1a1a1a&color=fff&name=' . urlencode(fake()->name()),
            'riding_style' => fake()->randomElement(['Sportive', 'Roadtrip', 'Tout-Terrain', 'Custom']),
            'bio' => fake()->sentence(),
            'role' => 'user',
        ];
    }
}
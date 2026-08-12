<?php

namespace Database\Seeders;

use App\Models\Accommodation;
use Illuminate\Database\Seeder;

class AccommodationSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'Riad Atlas Marrakech', 'type' => 'riad', 'latitude' => 31.6295, 'longitude' => -7.9811, 'price_per_night' => '80€', 'is_sponsored' => true],
            ['name' => 'Camping Merzouga Désert', 'type' => 'camping', 'latitude' => 31.0997, 'longitude' => -4.0128, 'price_per_night' => '15€', 'is_sponsored' => false],
            ['name' => 'Auberge Kasbah Ouarzazate', 'type' => 'auberge', 'latitude' => 30.9189, 'longitude' => -6.8934, 'price_per_night' => '40€', 'is_sponsored' => false],
            ['name' => 'Hotel Chefchaouen Riviera', 'type' => 'hotel', 'latitude' => 35.1714, 'longitude' => -5.2696, 'price_per_night' => '65€', 'is_sponsored' => true],
        ];

        foreach ($data as $item) {
            Accommodation::create($item);
        }
    }
}
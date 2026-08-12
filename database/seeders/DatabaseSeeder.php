<?php

namespace Database\Seeders;

use App\Models\Accommodation;
use App\Models\Group;
use App\Models\GroupMessage;
use App\Models\LiveTracking;
use App\Models\Motorcycle;
use App\Models\Poi;
use App\Models\Rental;
use App\Models\Review;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Utilisateurs
        $admin = User::create([
            'name' => 'Admin MotoRaid', 'email' => 'admin@motoraid.ma', 'password' => bcrypt('password'),
            'role' => 'admin', 'riding_style' => 'Tout-Terrain', 'emergency_name' => 'Direction', 'emergency_phone' => '0600000000'
        ]);

        $partner = User::create([
            'name' => 'Agence Atlas Moto', 'email' => 'partner@motoraid.ma', 'password' => bcrypt('password'),
            'role' => 'partner', 'riding_style' => 'Roadtrip', 'emergency_name' => 'Patron', 'emergency_phone' => '0611111111'
        ]);

        $premiumUser = User::create([
            'name' => 'Karim Motard', 'email' => 'premium@motoraid.ma', 'password' => bcrypt('password'),
            'role' => 'premium', 'riding_style' => 'Sportive', 'emergency_name' => 'Fatima (Épouse)', 'emergency_phone' => '0622222222'
        ]);

        $normalUsers = User::factory(7)->create([
            'role' => 'user',
            'emergency_name' => 'Famille',
            'emergency_phone' => '06' . rand(10000000, 99999999)
        ]);

        $allUsers = $normalUsers->merge([$premiumUser]);

        // 2. Motos
        $motosData = [
            ['brand' => 'Yamaha', 'model' => 'Ténéré 700', 'daily_rate' => 45, 'quantity' => 3],
            ['brand' => 'BMW', 'model' => 'R 1250 GS', 'daily_rate' => 80, 'quantity' => 2],
            ['brand' => 'Honda', 'model' => 'Africa Twin', 'daily_rate' => 65, 'quantity' => 2],
            ['brand' => 'KTM', 'model' => '890 Adventure', 'daily_rate' => 70, 'quantity' => 1],
        ];

        $motorcycles = [];
        foreach ($motosData as $data) {
            $motorcycles[] = Motorcycle::create(array_merge($data, [
                'user_id' => $partner->id,
                'year' => 2023,
                'plate_number' => rand(10000, 99999) . '-' . chr(rand(65, 90)) . '-' . rand(1, 99),
                'serial_number' => strtoupper(substr(md5(time() . rand()), 0, 10)),
                'deposit' => $data['daily_rate'] * 10,
                'description' => "Moto d'exception, parfaite pour les routes du Maroc.",
                'image_url' => null,
                'status' => 'available',
            ]));
        }

        // 3. Locations
        Rental::create([
            'motorcycle_id' => $motorcycles[0]->id, 'renter_id' => $premiumUser->id,
            'start_date' => now()->addDays(2)->toDateString(), 'end_date' => now()->addDays(6)->toDateString(),
            'total_price' => 225.00, 'status' => 'active', 'payment_status' => 'paid',
            'accessories' => ['Casque', 'Top-Case'], 'notes' => 'Je passerai chercher la moto à 10h.'
        ]);

        Rental::create([
            'motorcycle_id' => $motorcycles[1]->id, 'renter_id' => $allUsers[0]->id,
            'start_date' => now()->subDays(10)->toDateString(), 'end_date' => now()->subDays(5)->toDateString(),
            'total_price' => 400.00, 'status' => 'completed', 'payment_status' => 'paid',
            'accessories' => ['Casque', 'Gants'], 'notes' => null
        ]);

        // 4. Avis sur les Motos
        Review::create(['motorcycle_id' => $motorcycles[1]->id, 'user_id' => $allUsers[0]->id, 'rating' => 5, 'comment' => 'Excellente moto pour l\'Atlas.']);
        Review::create(['motorcycle_id' => $motorcycles[0]->id, 'user_id' => $allUsers[1]->id, 'rating' => 4, 'comment' => 'Très bonne Ténéré.']);
        Review::create(['motorcycle_id' => $motorcycles[2]->id, 'user_id' => $premiumUser->id, 'rating' => 5, 'comment' => 'L\'Africa Twin est un monstre.']);

        // 5. Groupes
        $group1 = Group::create([
            'name' => 'Les Routards de l\'Atlas', 'description' => 'Balade mensuelle dans l\'Atlas.',
            'owner_id' => $premiumUser->id, 'is_private' => false, 'is_approval_required' => true,
            'max_members' => 15, 'start_point' => 'Marrakech', 'end_point' => 'Oukaïmeden', 'ride_date' => now()->addDays(14)->toDateString()
        ]);
        $group1->members()->attach($premiumUser->id, ['role' => 'admin', 'status' => 'approved', 'joined_at' => now()]);
        $group1->members()->attach($allUsers[1]->id, ['role' => 'member', 'status' => 'approved', 'joined_at' => now()]);
        $group1->members()->attach($allUsers[2]->id, ['role' => 'member', 'status' => 'pending', 'joined_at' => now()]);

        GroupMessage::create(['group_id' => $group1->id, 'user_id' => $premiumUser->id, 'message' => 'Salut la team !']);
        GroupMessage::create(['group_id' => $group1->id, 'user_id' => $allUsers[1]->id, 'message' => 'Top ! Je serai là.']);

        // 6. POIs (On utilise create pour être sûr que les timestamps sont remplis)
        Poi::create(['user_id' => $admin->id, 'name' => 'Station V Tout-Terrain', 'type' => 'fuel', 'latitude' => 31.6295, 'longitude' => -7.9811, 'description' => 'Sans-Plomb 98']);
        Poi::create(['user_id' => $admin->id, 'name' => 'Mécano Hassan', 'type' => 'mechanic', 'latitude' => 31.1250, 'longitude' => -4.0410, 'description' => 'Répare tout']);
        Poi::create(['user_id' => $admin->id, 'name' => 'Café des Bikers', 'type' => 'food', 'latitude' => 30.9189, 'longitude' => -6.8934, 'description' => 'Bon petit déj']);
        Poi::create(['user_id' => $admin->id, 'name' => 'Point de vue Tizi n\'Tichka', 'type' => 'view', 'latitude' => 31.3012, 'longitude' => -7.3886, 'description' => 'Vue magnifique']);

        // 7. Hébergements
        Accommodation::create(['name' => 'Riad Atlas Marrakech', 'type' => 'riad', 'latitude' => 31.6295, 'longitude' => -7.9811, 'price_per_night' => '80€', 'is_sponsored' => true]);
        Accommodation::create(['name' => 'Camping Merzouga Désert', 'type' => 'camping', 'latitude' => 31.0997, 'longitude' => -4.0128, 'price_per_night' => '15€', 'is_sponsored' => false]);
        Accommodation::create(['name' => 'Auberge Kasbah Ouarzazate', 'type' => 'auberge', 'latitude' => 30.9189, 'longitude' => -6.8934, 'price_per_night' => '40€', 'is_sponsored' => false]);

        // 8. Témoignages
        Testimonial::create([
            'name' => 'Karim B.', 'role' => 'Motard à Casablanca', 'rating' => 5, 
            'content' => "J'ai loué une BMW GS pour le désert. Le paiement était sécurisé et la moto était impeccable. MotoRaid a changé ma façon de rouler au Maroc.", 
            'is_approved' => true
        ]);
        Testimonial::create([
            'name' => 'Youssef A.', 'role' => 'Leader du groupe Atlas Riders', 'rating' => 5, 
            'content' => "Le système de groupes et de tracking live est juste génial. On a organisé une balade de 15 motards en toute sécurité grâce au bouton SOS.", 
            'is_approved' => true
        ]);
        Testimonial::create([
            'name' => 'Sara M.', 'role' => 'Touriste en Moto', 'rating' => 4, 
            'content' => "L'assistant IA m'a aidée à tracer la meilleure route de Fès à Chefchaouen. Une expérience premium de A à Z !", 
            'is_approved' => true
        ]);

        // 9. Tracking Live
        LiveTracking::create(['user_id' => $premiumUser->id, 'latitude' => 31.6295, 'longitude' => -7.9811, 'speed' => 90.5, 'heading' => 180, 'status' => 'active']);
        LiveTracking::create(['user_id' => $allUsers[1]->id, 'latitude' => 31.1250, 'longitude' => -4.0410, 'speed' => 0, 'heading' => 0, 'status' => 'sos']);
    }
}
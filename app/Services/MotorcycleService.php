<?php

namespace App\Services;

use App\Models\Motorcycle;
use Illuminate\Database\Eloquent\Collection;

class MotorcycleService
{
    public function getAvailableMotorcycles(): Collection
    {
        // On récupère les motos disponibles avec les infos du propriétaire
        return Motorcycle::where('status', 'available')
            ->with('owner:id,name,avatar_url')
            ->latest()
            ->get();
    }

    public function createMotorcycle(array $data): Motorcycle
    {
        return Motorcycle::create($data);
    }
}
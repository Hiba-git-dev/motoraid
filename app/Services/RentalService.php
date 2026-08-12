<?php

namespace App\Services;

use App\Models\Motorcycle;
use App\Models\Rental;
use Carbon\Carbon;
use Exception;

class RentalService
{
    public function calculateTotalPrice(Motorcycle $motorcycle, string $startDate, string $endDate): float
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $days = $start->diffInDays($end) + 1;
        return $motorcycle->daily_rate * $days;
    }

    public function createRental(array $data, int $userId): Rental
    {
        $motorcycle = Motorcycle::findOrFail($data['motorcycle_id']);

        // 1. Vérifier le stock
        $bookedCount = Rental::where('motorcycle_id', $motorcycle->id)
            ->whereIn('status', ['pending', 'active'])
            ->where('start_date', '<=', $data['end_date'])
            ->where('end_date', '>=', $data['start_date'])
            ->count();

        if ($bookedCount >= $motorcycle->quantity) {
            throw new Exception("Désolé, le stock de cette moto est épuisé pour ces dates.");
        }

        $totalPrice = $this->calculateTotalPrice($motorcycle, $data['start_date'], $data['end_date']);

        // 2. Création de la location avec les accessoires et notes
        $rental = Rental::create([
            'motorcycle_id' => $motorcycle->id,
            'renter_id' => $userId,
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'total_price' => $totalPrice,
            'accessories' => $data['accessories'] ?? [],
            'notes' => $data['notes'] ?? null,
            'status' => 'active',
            'payment_status' => 'paid',
        ]);

        if ($bookedCount + 1 >= $motorcycle->quantity) {
            $motorcycle->update(['status' => 'rented']);
        }

        return $rental;
    }
}
<?php

namespace App\Services;

use App\Models\Group;
use App\Models\LiveTracking;
use App\Models\Motorcycle;
use App\Models\Rental;
use App\Models\User;

class StatsService
{
    public function getGlobalStats(): array
    {
        return [
            'total_users' => User::count(),
            'total_motorcycles' => Motorcycle::count(),
            'total_revenue' => Rental::where('payment_status', 'paid')->sum('total_price') ?? 0,
            'pending_rentals' => Rental::where('status', 'pending')->count(),
            'total_groups' => Group::count(),
            'active_sos' => LiveTracking::where('status', 'sos')->count(),
        ];
    }

    public function getRecentRentals(): \Illuminate\Database\Eloquent\Collection
    {
        return Rental::with(['motorcycle:id,brand,model', 'renter:id,name'])
            ->latest()
            ->limit(5)
            ->get();
    }

    public function getMonthlyRentalsData(): array
    {
        $rentals = Rental::selectRaw('strftime("%Y-%m", created_at) as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month');

        $labels = [];
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $labels[] = $date->format('M');
            $data[] = $rentals[$monthKey] ?? 0;
        }

        return ['labels' => $labels, 'data' => $data];
    }
}
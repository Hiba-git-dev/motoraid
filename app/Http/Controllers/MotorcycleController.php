<?php

namespace App\Http\Controllers;

use App\Models\Motorcycle;
use App\Models\Rental;
use App\Services\MotorcycleService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MotorcycleController extends Controller
{
    public function __construct(private MotorcycleService $motorcycleService) {}

    public function index(Request $request)
    {
        $motorcycles = $this->motorcycleService->getAvailableMotorcycles();
        
        // On récupère aussi les réservations de l'utilisateur pour les afficher sur le côté
        $myRentals = Rental::where('renter_id', $request->user()->id)
            ->with('motorcycle:id,brand,model,image_url,daily_rate')
            ->latest()
            ->get();

        return Inertia::render('Rentals/Index', [
            'motorcycles' => $motorcycles,
            'myRentals' => $myRentals
        ]);
    }

    public function show($id)
    {
        $motorcycle = Motorcycle::with(['owner:id,name,avatar_url', 'reviews.user:id,name'])->findOrFail($id);
        $averageRating = $motorcycle->reviews->avg('rating') ?? 0;

        return Inertia::render('Rentals/Show', [
            'moto' => $motorcycle,
            'averageRating' => round($averageRating, 1)
        ]);
    }
}
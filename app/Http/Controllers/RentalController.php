<?php

namespace App\Http\Controllers;

use App\Models\Motorcycle;
use App\Models\Rental;
use App\Services\RentalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Carbon\Carbon;

class RentalController extends Controller
{
    public function __construct(private RentalService $rentalService) {}

    public function myRentals(Request $request)
    {
        $rentals = Rental::where('renter_id', $request->user()->id)
            ->with('motorcycle:id,brand,model,image_url,daily_rate')
            ->latest()
            ->get();

        return Inertia::render('Rentals/MyRentals', [
            'rentals' => $rentals
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'motorcycle_id' => 'required|exists:motorcycles,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'accessories' => 'nullable|array',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            // MODE SIMULATION : On crée la location directement sans passer par PayPal
            $rental = $this->rentalService->createRental($validated, $request->user()->id);
            
            // On notifie l'utilisateur
            $rental->load('motorcycle:id,brand,model');
            $request->user()->notify(new \App\Notifications\RentalConfirmed($rental));

            return redirect()->route('rentals.my')->with('success', 'Paiement réussi (Simulé) ! Votre location est confirmée.');

        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }
}
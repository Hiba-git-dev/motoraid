<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'motorcycle_id' => 'required|exists:motorcycles,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        // Sécurité : Vérifier que l'utilisateur a bien loué cette moto dans le passé
        $hasRented = Rental::where('renter_id', $request->user()->id)
            ->where('motorcycle_id', $validated['motorcycle_id'])
            ->where('end_date', '<', now())
            ->exists();

        if (!$hasRented) {
            return Redirect::back()->with('error', 'Vous ne pouvez laisser un avis que sur une moto que vous avez réellement louée.');
        }

        // Un utilisateur ne peut laisser qu'un avis par moto
        Review::updateOrCreate(
            ['motorcycle_id' => $validated['motorcycle_id'], 'user_id' => $request->user()->id],
            $validated
        );

        return Redirect::back()->with('success', 'Merci pour votre avis sur cette location !');
    }
}
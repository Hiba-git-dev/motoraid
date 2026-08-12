<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TestimonialController extends Controller
{
    // Permet à un utilisateur connecté de soumettre un témoignage
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'role' => 'required|string|max:50',
            'content' => 'required|string|max:500',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Par défaut, le témoignage n'est pas approuvé (l'admin doit le valider)
        $validated['is_approved'] = false;
        
        Testimonial::create($validated);

        return Redirect::back()->with('success', 'Merci pour votre témoignage ! Il sera visible après validation par un administrateur.');
    }

    // Approuver un témoignage pour l'afficher sur la page d'accueil (Réservé à l'admin)
    public function approve(Testimonial $testimonial)
    {
        $testimonial->update(['is_approved' => true]);
        return redirect()->back()->with('success', 'Témoignage approuvé et publié.');
    }
}
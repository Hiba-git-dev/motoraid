<?php

namespace App\Http\Controllers;

use App\Models\Poi;
use Illuminate\Http\Request;

class PoiController extends Controller
{
    // Renvoie tous les POIs pour les afficher sur la carte
    public function index()
    {
        return response()->json(Poi::all());
    }

    // Enregistre un nouveau POI ajouté par un utilisateur
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:fuel,mechanic,food,water,view',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'description' => 'nullable|string|max:500',
        ]);

        Poi::create(array_merge($validated, ['user_id' => $request->user()->id]));

        return response()->json(['success' => true, 'message' => 'POI ajouté à la carte !']);
    }
}
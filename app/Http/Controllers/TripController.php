<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Services\GpxService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TripController extends Controller
{
    public function index(Request $request)
    {
        $myTrips = Trip::where('user_id', $request->user()->id)->latest()->get();

        return Inertia::render('Planner/Index', [
            'myTrips' => $myTrips
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'distance_km' => 'required|numeric',
            'estimated_duration' => 'required|integer',
            'route_data' => 'required|array',
        ]);

        $trip = $request->user()->trips()->create([
            'title' => $validated['title'],
            'distance_km' => $validated['distance_km'],
            'estimated_duration' => $validated['estimated_duration'],
            'route_data' => $validated['route_data'],
            'is_public' => false,
        ]);

        return redirect()->back()->with('success', 'Trajet sauvegardé !')->with('trip_id', $trip->id);
    }

    public function startRide(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'distance_km' => 'required|numeric',
            'estimated_duration' => 'required|integer',
            'route_data' => 'required|array',
        ]);

        $trip = $request->user()->trips()->create([
            'title' => $validated['title'],
            'distance_km' => $validated['distance_km'],
            'estimated_duration' => $validated['estimated_duration'],
            'route_data' => $validated['route_data'],
            'is_public' => false,
        ]);

        // Redirection DIRECTE vers le tracking avec l'ID du trajet
        return redirect()->route('tracking.index', ['trip' => $trip->id])->with('success', 'Bonne route ! Tracking activé.');
    }

    public function exportGpx(Trip $trip, GpxService $gpxService)
    {
        $gpxContent = $gpxService->generateGpx($trip);
        return response($gpxContent, 200, [
            'Content-Type' => 'application/gpx+xml',
            'Content-Disposition' => 'attachment; filename="motoraid-trip-' . $trip->id . '.gpx"',
        ]);
    }
}
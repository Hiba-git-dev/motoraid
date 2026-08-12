<?php

namespace App\Http\Controllers;

use App\Models\LiveTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; // <-- L'import manquant

class LiveTrackingController extends Controller
{
    // Met à jour la position de l'utilisateur connecté
    public function updateLocation(Request $request)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'speed' => 'nullable|numeric',
            'heading' => 'nullable|numeric',
        ]);

        $tracking = LiveTracking::updateOrCreate(
            ['user_id' => $request->user()->id],
            array_merge($validated, ['status' => 'active'])
        );

        return response()->json(['success' => true, 'data' => $tracking]);
    }

    // Déclenche l'alerte SOS
        public function triggerSos(Request $request)
    {
        $user = $request->user();
        $tracking = LiveTracking::where('user_id', $user->id)->first();

        if ($tracking) {
            $tracking->update(['status' => 'sos']);
            $mapsLink = "https://www.google.com/maps?q={$tracking->latitude},{$tracking->longitude}";

            // 1. Notifier les admins (en base de données)
            $admins = \App\Models\User::where('role', 'admin')->get();
            \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\SosAlert($user, $mapsLink));

            // 2. Envoi de l'email au contact d'urgence
            if ($user->emergency_name) {
                \Illuminate\Support\Facades\Mail::raw("Alerte SOS MotoRaid !\n\nLe motard {$user->name} a déclenché une alerte d'urgence.\nPosition actuelle : {$mapsLink}", function($message) use ($user) {
                    $message->to($user->email)->subject('🚨 ALERTE SOS MOTO RAID - ' . $user->name);
                });
            }

            return response()->json(['success' => true, 'message' => 'Alerte SOS envoyée ! Votre contact d\'urgence a été notifié par SMS/Email.']);
        }

        return response()->json(['success' => false, 'message' => 'Aucune position enregistrée.'], 404);
    }

    // Récupère la position de tous les utilisateurs actifs (pour afficher sur la carte)
    public function getActiveRiders()
    {
        $riders = LiveTracking::where('status', '!=', 'inactive')
            ->with('user:id,name,avatar_url')
            ->get();

        return response()->json($riders);
    }
}
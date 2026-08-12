<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AdminController;
use App\Models\Group;
use App\Models\Rental;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Si c'est un admin ou un partenaire, on affiche le grand Dashboard Admin
        if ($request->user()->role === 'admin' || $request->user()->role === 'partner') {
            return app(AdminController::class)->dashboard();
        }

        // Sinon, c'est un utilisateur normal, on prépare SES données
        $user = $request->user();

        $upcomingRentals = Rental::where('renter_id', $user->id)
            ->whereIn('status', ['pending', 'active'])
            ->with('motorcycle:id,brand,model,image_url,daily_rate')
            ->latest()
            ->get();

        $pastRentalsCount = Rental::where('renter_id', $user->id)->where('status', 'completed')->count();
        
        $myGroups = Group::whereHas('members', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->withCount('members')->get();

        $totalSpent = Rental::where('renter_id', $user->id)->where('payment_status', 'paid')->sum('total_price');

        // On affiche le Dashboard Utilisateur
        return Inertia::render('UserDashboard', [
            'upcomingRentals' => $upcomingRentals,
            'pastRentalsCount' => $pastRentalsCount,
            'myGroups' => $myGroups,
            'totalSpent' => $totalSpent,
        ]);
    }
}
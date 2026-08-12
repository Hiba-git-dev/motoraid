<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\LiveTracking;
use App\Models\Motorcycle;
use App\Models\Rental;
use App\Models\User;
use App\Services\StatsService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function __construct(private StatsService $statsService) {}

    public function dashboard()
    {
        $stats = $this->statsService->getGlobalStats();
        $recentRentals = $this->statsService->getRecentRentals();
        $chartData = $this->statsService->getMonthlyRentalsData();
        
        $pendingRentals = Rental::where('status', 'pending')->with(['motorcycle:id,brand,model', 'renter:id,name'])->latest()->get();
        $users = User::select('id', 'name', 'email', 'role', 'created_at')->latest()->get();
        $fleet = Motorcycle::with('owner:id,name')->latest()->get();
        $partners = User::where('role', 'partner')->orWhere('role', 'admin')->get(['id', 'name']);
        $groups = Group::withCount('members')->with('owner:id,name')->latest()->get();
        $sosAlerts = LiveTracking::where('status', 'sos')->with('user:id,name')->get();

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recentRentals' => $recentRentals,
            'chartData' => $chartData,
            'pendingRentals' => $pendingRentals,
            'users' => $users,
            'fleet' => $fleet,
            'partners' => $partners,
            'groups' => $groups,
            'sosAlerts' => $sosAlerts
        ]);
    }

    public function storePartner(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'partner'
        ]);

        return redirect()->back()->with('success', 'Agence partenaire créée avec succès.');
    }

    public function storeMotorcycle(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'brand' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'year' => 'required|integer|min:1990|max:2024',
            'serial_number' => 'nullable|string|max:100',
            'plate_number' => 'nullable|string|max:20',
            'daily_rate' => 'required|numeric|min:0',
            'deposit' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('motorcycles', 'public');
            $validated['image_url'] = '/storage/' . $path;
        }

        $validated['status'] = 'available';
        Motorcycle::create($validated);

        return redirect()->back()->with('success', 'Moto ajoutée à la flotte.');
    }

    public function approveRental(Rental $rental)
    {
        $rental->update(['status' => 'active']);
        $rental->motorcycle()->update(['status' => 'rented']);
        return redirect()->back()->with('success', 'Location approuvée.');
    }

    public function rejectRental(Rental $rental)
    {
        $rental->update(['status' => 'canceled']);
        return redirect()->back()->with('success', 'Location refusée.');
    }

    public function destroyUser(User $user)
    {
        if($user->role === 'admin') return redirect()->back()->with('error', 'Action interdite.');
        $user->delete();
        return redirect()->back()->with('success', 'Utilisateur supprimé.');
    }

    public function destroyMotorcycle(Motorcycle $motorcycle)
    {
        $motorcycle->delete();
        return redirect()->back()->with('success', 'Moto retirée.');
    }

    public function destroyGroup(Group $group)
    {
        $group->delete();
        return redirect()->back()->with('success', 'Groupe supprimé.');
    }
}
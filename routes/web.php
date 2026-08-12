<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\LiveTrackingController;
use App\Http\Controllers\MotorcycleController;
use App\Http\Controllers\PoiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\AccommodationController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\NotificationController;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Testimonial;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'testimonials' => Testimonial::where('is_approved', true)->latest()->limit(6)->get()
    ]);
})->name('home');

// --- MotoRaid : Assistant IA (Accessible à tous, même non connecté) ---
Route::post('/ai/chat', [AiController::class, 'chat'])->name('ai.chat');

// Le routeur intelligent qui envoie l'admin sur AdminController et l'user sur UserDashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Routes Breeze (Profil)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // --- MotoRaid : Monétisation & Hébergements ---
    Route::get('/pricing', [SubscriptionController::class, 'pricingPage'])->name('pricing');
    Route::post('/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscribe');
    Route::get('/paypal/success', [SubscriptionController::class, 'paypalSuccess'])->name('paypal.success');
    Route::get('/accommodations', [AccommodationController::class, 'index'])->name('accommodations.index');

    // --- MotoRaid : Notifications ---
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.read');

    // --- MotoRaid : Location (Rentals) ---
    Route::get('/rentals', [MotorcycleController::class, 'index'])->name('rentals.index');
    Route::get('/rentals/{id}', [MotorcycleController::class, 'show'])->name('rentals.show');
    Route::post('/rentals', [RentalController::class, 'store'])->name('rentals.store');
    Route::get('/my-rentals', [RentalController::class, 'myRentals'])->name('rentals.my');

    // --- MotoRaid : Avis sur les motos (Reviews) ---
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // --- MotoRaid : Témoignages (Avis sur l'expérience MotoRaid) ---
    Route::post('/testimonials', [TestimonialController::class, 'store'])->name('testimonials.store');

    // --- MotoRaid : Points d'Intérêt (POIs) ---
    Route::get('/pois', [PoiController::class, 'index'])->name('pois.index');
    Route::post('/pois', [PoiController::class, 'store'])->name('pois.store');

    // --- MotoRaid : Planificateur GPS ---
    Route::get('/planner', [TripController::class, 'index'])->name('planner.index');
    Route::post('/trips', [TripController::class, 'store'])->name('trips.store');
    Route::post('/trips/start', [TripController::class, 'startRide'])->name('trips.start');
    Route::get('/trips/{trip}/export-gpx', [TripController::class, 'exportGpx'])->name('trips.export-gpx');

    // --- MotoRaid : Réseau Social ---
    Route::get('/social', [GroupController::class, 'index'])->name('social.index');
    Route::post('/social/groups', [GroupController::class, 'store'])->name('social.groups.store');
    Route::get('/social/groups/{group}', [GroupController::class, 'show'])->name('social.groups.show');
    Route::post('/social/groups/{group}/join', [GroupController::class, 'join'])->name('social.groups.join');
    Route::put('/social/groups/{group}/approve/{user}', [GroupController::class, 'approveMember'])->name('social.groups.approve');
    Route::post('/social/groups/{group}/messages', [GroupController::class, 'storeMessage'])->name('social.groups.messages');
    Route::delete('/social/groups/{group}', [GroupController::class, 'destroy'])->name('social.groups.destroy');
    
    // --- MotoRaid : Tracking Live & SOS ---
    Route::get('/tracking', function (Request $request) {
        $user = $request->user();
        if ($user->role === 'admin' || $user->role === 'partner') {
            $riders = \App\Models\LiveTracking::where('status', '!=', 'inactive')->with('user:id,name')->get();
            return Inertia::render('Tracking/Admin', ['riders' => $riders]);
        }
        $activeTrip = $request->has('trip') ? \App\Models\Trip::find($request->trip) : null;
        return Inertia::render('Tracking/Index', ['activeTrip' => $activeTrip]);
    })->name('tracking.index');
    Route::post('/tracking/update', [LiveTrackingController::class, 'updateLocation'])->name('tracking.update');
    Route::post('/tracking/sos', [LiveTrackingController::class, 'triggerSos'])->name('tracking.sos');
    Route::get('/tracking/riders', [LiveTrackingController::class, 'getActiveRiders'])->name('tracking.riders');

    // ====================================================================
    // ROUTES ADMINISTRATION (Sécurisées par le middleware 'admin')
    // ====================================================================
    Route::middleware('admin')->group(function () {
        Route::post('/admin/partners', [AdminController::class, 'storePartner'])->name('admin.partners.store');
        Route::post('/admin/motorcycles', [AdminController::class, 'storeMotorcycle'])->name('admin.motorcycles.store');
        Route::put('/admin/rentals/{rental}/approve', [AdminController::class, 'approveRental'])->name('admin.rentals.approve');
        Route::put('/admin/rentals/{rental}/reject', [AdminController::class, 'rejectRental'])->name('admin.rentals.reject');
        Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
        Route::delete('/admin/motorcycles/{motorcycle}', [AdminController::class, 'destroyMotorcycle'])->name('admin.motorcycles.destroy');
        Route::delete('/admin/groups/{group}', [AdminController::class, 'destroyGroup'])->name('admin.groups.destroy');
        Route::put('/admin/testimonials/{testimonial}/approve', [TestimonialController::class, 'approve'])->name('admin.testimonials.approve');
    });
});

require __DIR__.'/auth.php';
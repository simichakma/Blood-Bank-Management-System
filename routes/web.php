<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Models\BloodInventory;
use App\Models\BloodDonation;
use App\Models\BloodRequest;
use App\Models\DonorProfile;
use App\Models\Hospital;
use Illuminate\Support\Facades\Route;

Route::get('/blood-banks', [\App\Http\Controllers\PublicPagesController::class, 'banks'])->name('banks.index');
Route::view('/blog', 'pages.blog')->name('blog.index');
Route::view('/about', 'pages.about')->name('about');

// Public frontend: reads live data directly from lifeblood_laravel.
Route::get('/', function () {
    $groups = ['A+','A-','B+','B-','AB+','AB-','O+','O-'];
    $inventory = BloodInventory::where('status','available')->get()->groupBy('blood_group');

    $featuredDonors = \App\Models\User::with('donorProfile')
        ->where('role', 'donor')->where('is_active', true)
        ->whereHas('donorProfile', fn ($query) => $query->where('eligible', true))
        ->latest()->limit(6)->get();

    return view('home', [
        'groups' => $groups,
        'inventory' => $inventory,
        'donorCount' => DonorProfile::count(),
        'hospitalCount' => Hospital::where('status','approved')->count(),
        'requestCount' => BloodRequest::whereIn('status',['pending','approved'])->count(),
        'livesSaved' => BloodDonation::where('screening_status', 'approved')->sum('units') * 3,
        'districtCount' => DonorProfile::whereNotNull('city')->distinct('city')->count('city'),
        'featuredDonors' => $featuredDonors,
        'recentDonors' => \App\Models\User::with('donorProfile')->where('role','donor')->latest()->limit(8)->get(),
        'recentHospitals' => Hospital::latest()->limit(8)->get(),
        'recentRequests' => BloodRequest::with('hospital')->latest()->limit(8)->get(),
    ]);
})->name('home');

// Public donor directory with full filtering and sorting.
Route::get('/donors', function (\Illuminate\Http\Request $request) {
    $locations = [
        'Dhaka' => ['Dhaka','Faridpur','Gazipur','Gopalganj','Kishoreganj','Madaripur','Manikganj','Munshiganj','Narayanganj','Narsingdi','Rajbari','Shariatpur','Tangail'],
        'Chattogram' => ['Bandarban','Brahmanbaria','Chandpur','Chattogram','Cox’s Bazar','Cumilla','Feni','Khagrachhari','Lakshmipur','Noakhali','Rangamati'],
        'Rajshahi' => ['Bogura','Joypurhat','Naogaon','Natore','Nawabganj','Pabna','Rajshahi','Sirajganj'],
        'Khulna' => ['Bagerhat','Chuadanga','Jashore','Jhenaidah','Khulna','Kushtia','Magura','Meherpur','Narail','Satkhira'],
        'Barishal' => ['Barguna','Barishal','Bhola','Jhalokathi','Patuakhali','Pirojpur'],
        'Sylhet' => ['Habiganj','Moulvibazar','Sunamganj','Sylhet'],
        'Rangpur' => ['Dinajpur','Gaibandha','Kurigram','Lalmonirhat','Nilphamari','Panchagarh','Rangpur','Thakurgaon'],
        'Mymensingh' => ['Jamalpur','Mymensingh','Netrokona','Sherpur'],
    ];

    $query = DonorProfile::query()
        ->with(['user' => fn ($user) => $user->withCount('donations')])
        ->whereHas('user', fn ($user) => $user->where('is_active', true));

    if ($request->filled('search')) {
        $term = trim((string) $request->string('search'));
        $query->where(function ($q) use ($term) {
            $q->where('address', 'like', "%{$term}%")
              ->orWhere('city', 'like', "%{$term}%")
              ->orWhereHas('user', fn ($user) => $user->where('name', 'like', "%{$term}%"));
        });
    }
    if ($request->filled('division') && isset($locations[(string) $request->string('division')])) {
        $districts = $locations[(string) $request->string('division')];
        $query->where(function ($q) use ($districts) {
            $q->whereIn('city', $districts);
            foreach ($districts as $district) $q->orWhere('address', 'like', "%{$district}%");
        });
    }
    if ($request->filled('district')) {
        $district = (string) $request->string('district');
        $query->where(fn ($q) => $q->where('city', $district)->orWhere('address', 'like', "%{$district}%"));
    }
    if ($request->filled('thana')) $query->where('address', 'like', '%'.(string) $request->string('thana').'%');
    if ($request->filled('blood_group')) $query->where('blood_group', (string) $request->string('blood_group'));
    if ($request->filled('gender')) $query->whereRaw('LOWER(gender) = ?', [mb_strtolower((string) $request->string('gender'))]);
    if ($request->get('availability') === 'available') $query->where('eligible', true);
    if ($request->get('availability') === 'unavailable') $query->where('eligible', false);

    $donors = $query->get();
    $sort = (string) $request->get('sort', 'name');
    $donors = (match ($sort) {
        'newest' => $donors->sortByDesc('created_at'),
        'blood_group' => $donors->sortBy('blood_group'),
        'last_donation' => $donors->sortByDesc('last_donation_date'),
        default => $donors->sortBy(fn ($profile) => strtolower($profile->user?->name ?? '')),
    })->values();

    return view('donors.index', [
        'donors' => $donors,
        'groups' => ['A+','A-','B+','B-','AB+','AB-','O+','O-'],
        'locations' => $locations,
    ]);
})->name('donors.index');

// Public donor search used by the location and blood-group filters on the home page.
Route::get('/api/donors/search', function (\Illuminate\Http\Request $request) {
    $query = DonorProfile::query()
        ->with('user:id,name,email,phone,is_active')
        ->where('eligible', true)
        ->whereHas('user', fn ($user) => $user->where('is_active', true));

    if ($request->filled('blood_group')) {
        $query->where('blood_group', (string) $request->string('blood_group'));
    }
    if ($request->filled('district')) {
        $district = (string) $request->string('district');
        $query->where(function ($q) use ($district) {
            $q->whereRaw('LOWER(city) = ?', [mb_strtolower($district)])
              ->orWhere('address', 'like', "%{$district}%");
        });
    }
    if ($request->filled('thana')) {
        $query->where('address', 'like', '%'.(string) $request->string('thana').'%');
    }

    return $query->latest()->limit(50)->get()->map(fn ($profile) => [
        'id' => $profile->id,
        'name' => $profile->user?->name ?? 'Blood Donor',
        'blood_group' => $profile->blood_group,
        'phone' => $profile->user?->phone,
        'email' => $profile->user?->email,
        'district' => $profile->city,
        'address' => $profile->address,
        'last_donation_date' => optional($profile->last_donation_date)->format('d M Y'),
    ]);
})->name('donors.search');


// Public district-wise hospital lookup for the frontend.
Route::get('/api/hospitals/by-district/{district}', function (string $district) {
    return Hospital::query()
        ->where('status', 'approved')
        ->whereRaw('LOWER(city) = ?', [mb_strtolower($district)])
        ->orderBy('hospital_name')
        ->get(['id', 'hospital_name', 'address', 'city', 'phone', 'status']);
})->name('hospitals.byDistrict');

// Public dashboard/overview. No login is required.
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Backend CRUD. No login/authentication is used at this stage.
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');

    Route::post('/hospitals/{hospital}/approve', [AdminController::class, 'approveHospital'])->name('hospitals.approve');
    Route::post('/requests/{bloodRequest}/status', [AdminController::class, 'updateRequest'])->name('requests.status');

    Route::post('/donors', [AdminController::class, 'donorStore'])->name('donors.store');
    Route::put('/donors/{donor}', [AdminController::class, 'donorUpdate'])->name('donors.update');
    Route::delete('/donors/{donor}', [AdminController::class, 'donorDelete'])->name('donors.delete');

    Route::post('/hospitals', [AdminController::class, 'hospitalStore'])->name('hospitals.store');
    Route::put('/hospitals/{hospital}', [AdminController::class, 'hospitalUpdate'])->name('hospitals.update');
    Route::delete('/hospitals/{hospital}', [AdminController::class, 'hospitalDelete'])->name('hospitals.delete');

    Route::post('/inventory', [AdminController::class, 'inventoryStore'])->name('inventory.store');
    Route::put('/inventory/{inventory}', [AdminController::class, 'inventoryUpdate'])->name('inventory.update');
    Route::delete('/inventory/{inventory}', [AdminController::class, 'inventoryDelete'])->name('inventory.delete');

    Route::post('/donations', [AdminController::class, 'donationStore'])->name('donations.store');
    Route::put('/donations/{donation}', [AdminController::class, 'donationUpdate'])->name('donations.update');
    Route::delete('/donations/{donation}', [AdminController::class, 'donationDelete'])->name('donations.delete');

    Route::post('/appointments', [AdminController::class, 'appointmentStore'])->name('appointments.store');
    Route::put('/appointments/{appointment}', [AdminController::class, 'appointmentUpdate'])->name('appointments.update');
    Route::delete('/appointments/{appointment}', [AdminController::class, 'appointmentDelete'])->name('appointments.delete');

    Route::post('/requests', [AdminController::class, 'requestStore'])->name('requests.store');
    Route::put('/requests/{bloodRequest}', [AdminController::class, 'requestUpdateFull'])->name('requests.update');
    Route::delete('/requests/{bloodRequest}', [AdminController::class, 'requestDelete'])->name('requests.delete');
});

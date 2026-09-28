<?php
namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\BloodDonation;
use App\Models\BloodInventory;
use App\Models\BloodRequest;
use App\Models\DonorProfile;
use App\Models\Hospital;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.public', [
            'donors' => User::where('role','donor')->count(),
            'hospitals' => Hospital::count(),
            'approvedHospitals' => Hospital::where('status','approved')->count(),
            'requests' => BloodRequest::count(),
            'pendingRequests' => BloodRequest::where('status','pending')->count(),
            'donations' => BloodDonation::count(),
            'appointments' => Appointment::count(),
            'availableUnits' => BloodInventory::where('status','available')->sum('units'),
            'inventory' => BloodInventory::where('status','available')->get()->groupBy('blood_group'),
            'recentRequests' => BloodRequest::with('hospital')->latest()->limit(10)->get(),
        ]);
    }
}

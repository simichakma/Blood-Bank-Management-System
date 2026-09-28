<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\BloodDonation;
use App\Models\BloodInventory;
use App\Models\BloodRequest;
use App\Models\DonorProfile;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    private array $groups = ['A+','A-','B+','B-','AB+','AB-','O+','O-'];

    public function index(Request $request)
    {
        $editType = $request->get('edit');
        $editId = $request->get('id');
        $editing = null;

        if ($editType && $editId) {
            $editing = match ($editType) {
                'donor' => User::with('donorProfile')->where('role','donor')->findOrFail($editId),
                'hospital' => Hospital::with('user')->findOrFail($editId),
                'inventory' => BloodInventory::with('hospital')->findOrFail($editId),
                'donation' => BloodDonation::with('donor')->findOrFail($editId),
                'appointment' => Appointment::with('donor')->findOrFail($editId),
                'request' => BloodRequest::with('hospital')->findOrFail($editId),
                default => null,
            };
        }

        return view('admin.index', [
            'donors' => User::with('donorProfile')->where('role','donor')->latest()->get(),
            'hospitals' => Hospital::with('user')->latest()->get(),
            'inventoryRows' => BloodInventory::with('hospital')->latest()->get(),
            'donationsRows' => BloodDonation::with('donor')->latest()->get(),
            'appointmentsRows' => Appointment::with('donor')->latest()->get(),
            'requestsRows' => BloodRequest::with('hospital')->latest()->get(),
            'editing' => $editing,
            'editType' => $editType,
            'groups' => $this->groups,
            'donorUsers' => User::with('donorProfile')->where('role','donor')->orderBy('name')->get(),
            'hospitalRows' => Hospital::orderBy('hospital_name')->get(),
        ]);
    }

    public function approveHospital(Hospital $hospital)
    {
        $hospital->update(['status'=>'approved']);
        return back()->with('success','Hospital approved.');
    }

    public function updateRequest(Request $request, BloodRequest $bloodRequest)
    {
        $data = $request->validate(['status'=>['required', Rule::in(['pending','approved','fulfilled','rejected','cancelled'])]]);
        $bloodRequest->update($data);
        return back()->with('success','Request status updated.');
    }

    public function donorStore(Request $request)
    {
        $data = $this->donorValidation($request);
        DB::transaction(function () use ($data) {
            $user = User::create([
                'name'=>$data['name'], 'email'=>$data['email'], 'phone'=>$data['phone'] ?? null,
                'password'=>Hash::make($data['password'] ?? 'Donor@12345'),
                'role'=>'donor', 'is_active'=>$data['is_active'] ?? true,
            ]);
            DonorProfile::create([
                'user_id'=>$user->id, 'blood_group'=>$data['blood_group'],
                'date_of_birth'=>$data['date_of_birth'] ?? null, 'gender'=>$data['gender'] ?? null,
                'address'=>$data['address'] ?? null, 'city'=>$data['city'] ?? null,
                'last_donation_date'=>$data['last_donation_date'] ?? null,
                'eligible'=>$data['eligible'] ?? true, 'emergency_contact'=>$data['emergency_contact'] ?? null,
            ]);
        });
        return back()->with('success','Donor added successfully.');
    }

    public function donorUpdate(Request $request, User $donor)
    {
        abort_unless($donor->role === 'donor', 404);
        $data = $this->donorValidation($request, $donor->id);
        DB::transaction(function () use ($data, $donor) {
            $donor->update([
                'name'=>$data['name'], 'email'=>$data['email'], 'phone'=>$data['phone'] ?? null,
                'is_active'=>(bool)($data['is_active'] ?? false),
            ]);
            if (!empty($data['password'])) $donor->update(['password'=>Hash::make($data['password'])]);
            $donor->donorProfile()->updateOrCreate(['user_id'=>$donor->id], [
                'blood_group'=>$data['blood_group'],
                'date_of_birth'=>$data['date_of_birth'] ?? null, 'gender'=>$data['gender'] ?? null,
                'address'=>$data['address'] ?? null, 'city'=>$data['city'] ?? null,
                'last_donation_date'=>$data['last_donation_date'] ?? null,
                'eligible'=>(bool)($data['eligible'] ?? false), 'emergency_contact'=>$data['emergency_contact'] ?? null,
            ]);
        });
        return redirect()->route('admin.index')->with('success','Donor updated successfully.');
    }

    public function donorDelete(User $donor)
    {
        abort_unless($donor->role === 'donor', 404);
        $donor->delete();
        return back()->with('success','Donor deleted successfully.');
    }

    private function donorValidation(Request $request, $id=null): array
    {
        return $request->validate([
            'name'=>'required|string|max:120',
            'email'=>['required','email','max:255',Rule::unique('users','email')->ignore($id)],
            'phone'=>'nullable|string|max:30',
            'password'=>'nullable|string|min:8',
            'blood_group'=>['required',Rule::in($this->groups)],
            'date_of_birth'=>'nullable|date','gender'=>'nullable|string|max:20',
            'address'=>'nullable|string|max:255','city'=>'nullable|string|max:100',
            'last_donation_date'=>'nullable|date','emergency_contact'=>'nullable|string|max:30',
            'is_active'=>'nullable|boolean','eligible'=>'nullable|boolean',
        ]);
    }

    public function hospitalStore(Request $request)
    {
        $data=$this->hospitalValidation($request);
        DB::transaction(function() use($data){
            $user=User::create([
                'name'=>$data['contact_person'] ?: $data['hospital_name'],
                'email'=>$data['email'],'phone'=>$data['phone'] ?? null,
                'password'=>Hash::make($data['password'] ?? 'Hospital@12345'),
                'role'=>'hospital','is_active'=>$data['is_active'] ?? true
            ]);
            Hospital::create([
                'user_id'=>$user->id,'hospital_name'=>$data['hospital_name'],
                'registration_no'=>$data['registration_no'],'contact_person'=>$data['contact_person'] ?? null,
                'address'=>$data['address'] ?? null,'city'=>$data['city'] ?? null,
                'phone'=>$data['phone'] ?? null,'status'=>$data['status']
            ]);
        });
        return back()->with('success','Hospital added successfully.');
    }

    public function hospitalUpdate(Request $request, Hospital $hospital)
    {
        $data=$this->hospitalValidation($request,$hospital->id,$hospital->user_id);
        DB::transaction(function() use($data,$hospital){
            $hospital->update([
                'hospital_name'=>$data['hospital_name'],'registration_no'=>$data['registration_no'],
                'contact_person'=>$data['contact_person'] ?? null,'address'=>$data['address'] ?? null,
                'city'=>$data['city'] ?? null,'phone'=>$data['phone'] ?? null,'status'=>$data['status']
            ]);
            if($hospital->user){
                $hospital->user->update([
                    'name'=>$data['contact_person'] ?: $data['hospital_name'],
                    'email'=>$data['email'],'phone'=>$data['phone'] ?? null,
                    'is_active'=>$data['is_active'] ?? false
                ]);
                if(!empty($data['password'])) $hospital->user->update(['password'=>Hash::make($data['password'])]);
            }
        });
        return redirect()->route('admin.index')->with('success','Hospital updated successfully.');
    }

    public function hospitalDelete(Hospital $hospital)
    {
        $hospital->user?->delete();
        if($hospital->exists) $hospital->delete();
        return back()->with('success','Hospital deleted successfully.');
    }

    private function hospitalValidation(Request $request,$hospitalId=null,$userId=null): array
    {
        return $request->validate([
            'hospital_name'=>'required|string|max:180',
            'registration_no'=>['required','string','max:100',Rule::unique('hospitals','registration_no')->ignore($hospitalId)],
            'contact_person'=>'nullable|string|max:120',
            'email'=>['required','email','max:255',Rule::unique('users','email')->ignore($userId)],
            'phone'=>'nullable|string|max:30','address'=>'nullable|string|max:255',
            'city'=>'nullable|string|max:100','password'=>'nullable|string|min:8',
            'status'=>['required',Rule::in(['pending','approved','suspended'])],
            'is_active'=>'nullable|boolean',
        ]);
    }

    public function inventoryStore(Request $request)
    {
        $data=$request->validate([
            'hospital_id'=>'nullable|integer|exists:hospitals,id',
            'blood_group'=>['required',Rule::in($this->groups)],'units'=>'required|integer|min:0',
            'storage_location'=>'nullable|string|max:150','expiry_date'=>'nullable|date',
            'status'=>['required',Rule::in(['available','reserved','expired'])]
        ]);
        BloodInventory::create($data);
        return back()->with('success','Inventory entry added successfully.');
    }

    public function inventoryUpdate(Request $request,BloodInventory $inventory)
    {
        $data=$request->validate([
            'hospital_id'=>'nullable|integer|exists:hospitals,id',
            'blood_group'=>['required',Rule::in($this->groups)],'units'=>'required|integer|min:0',
            'storage_location'=>'nullable|string|max:150','expiry_date'=>'nullable|date',
            'status'=>['required',Rule::in(['available','reserved','expired'])]
        ]);
        $inventory->update($data);
        return redirect()->route('admin.index')->with('success','Inventory updated successfully.');
    }

    public function inventoryDelete(BloodInventory $inventory)
    {
        $inventory->delete(); return back()->with('success','Inventory deleted successfully.');
    }

    public function donationStore(Request $request)
    {
        $data=$this->donationValidation($request);
        BloodDonation::create($data);
        return back()->with('success','Donation record added successfully.');
    }

    public function donationUpdate(Request $request,BloodDonation $donation)
    {
        $donation->update($this->donationValidation($request));
        return redirect()->route('admin.index')->with('success','Donation updated successfully.');
    }

    private function donationValidation(Request $request): array
    {
        return $request->validate([
            'donor_id'=>['required','integer',Rule::exists('users','id')->where(fn($q)=>$q->where('role','donor'))],
            'blood_group'=>['required',Rule::in($this->groups)],'units'=>'required|integer|min:1',
            'donated_at'=>'required|date','screening_status'=>['required',Rule::in(['pending','approved','rejected'])],
            'notes'=>'nullable|string|max:2000'
        ]);
    }

    public function donationDelete(BloodDonation $donation)
    {
        $donation->delete(); return back()->with('success','Donation deleted successfully.');
    }

    public function appointmentStore(Request $request)
    {
        Appointment::create($this->appointmentValidation($request));
        return back()->with('success','Appointment added successfully.');
    }

    public function appointmentUpdate(Request $request,Appointment $appointment)
    {
        $appointment->update($this->appointmentValidation($request));
        return redirect()->route('admin.index')->with('success','Appointment updated successfully.');
    }

    private function appointmentValidation(Request $request): array
    {
        return $request->validate([
            'donor_id'=>['required','integer',Rule::exists('users','id')->where(fn($q)=>$q->where('role','donor'))],
            'appointment_at'=>'required|date','location'=>'nullable|string|max:150',
            'status'=>['required',Rule::in(['scheduled','completed','cancelled'])],
            'notes'=>'nullable|string|max:2000'
        ]);
    }

    public function appointmentDelete(Appointment $appointment)
    {
        $appointment->delete(); return back()->with('success','Appointment deleted successfully.');
    }

    public function requestStore(Request $request)
    {
        $data = $this->requestValidation($request);
        BloodRequest::create($data);
        return back()->with('success','Blood request added successfully.');
    }

    private function requestValidation(Request $request): array
    {
        return $request->validate([
            'hospital_id'=>['required','integer','exists:hospitals,id'],
            'patient_name'=>'required|string|max:150',
            'patient_age'=>'nullable|integer|min:0|max:130',
            'blood_group'=>['required',Rule::in($this->groups)],
            'units_required'=>'required|integer|min:1|max:50',
            'urgency'=>['required',Rule::in(['normal','urgent','critical'])],
            'needed_by'=>'nullable|date',
            'reason'=>'nullable|string|max:2000',
            'notes'=>'nullable|string|max:2000',
            'status'=>['required',Rule::in(['pending','approved','fulfilled','rejected','cancelled'])],
        ]);
    }

    public function requestUpdateFull(Request $request,BloodRequest $bloodRequest)
    {
        $bloodRequest->update($this->requestValidation($request));
        return redirect()->route('admin.index')->with('success','Blood request updated successfully.');
    }

    public function requestDelete(BloodRequest $bloodRequest)
    {
        $bloodRequest->delete(); return back()->with('success','Blood request deleted successfully.');
    }
}

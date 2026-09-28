<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\BloodDonation;
use App\Models\BloodInventory;
use App\Models\BloodRequest;
use App\Models\DonorProfile;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Creates a realistic demo dataset so the application looks populated
     * immediately after `php artisan db:seed`.
     *
     * All demo accounts use the `demo.` email prefix, making the dataset
     * easy to identify and safe to manage from the Admin Panel.
     */
    public function run(): void
    {
        $now = now();

        $admin = User::updateOrCreate(['email' => 'admin@lifeblood.test'], [
            'name' => 'LifeBlood System Admin',
            'password' => Hash::make('Admin@12345'),
            'role' => 'admin',
            'phone' => '01700000000',
            'is_active' => true,
        ]);

        $donorData = [
            ['Rahim Uddin', 'rahim.uddin@demo.lifeblood.test', '01711000001', 'O+', 'Dhaka', 'Uttara Sector 7, Dhaka', 'Male'],
            ['Nusrat Jahan', 'nusrat.jahan@demo.lifeblood.test', '01711000002', 'A+', 'Dhaka', 'Dhanmondi, Dhaka', 'Female'],
            ['Tanvir Hasan', 'tanvir.hasan@demo.lifeblood.test', '01711000003', 'B+', 'Gazipur', 'Gazipur Sadar, Gazipur', 'Male'],
            ['Mou Sultana', 'mou.sultana@demo.lifeblood.test', '01711000004', 'AB+', 'Chattogram', 'Panchlaish, Chattogram', 'Female'],
            ['Sabbir Ahmed', 'sabbir.ahmed@demo.lifeblood.test', '01711000005', 'O-', 'Cumilla', 'Kotbari, Cumilla', 'Male'],
            ['Jannatul Ferdous', 'jannatul.ferdous@demo.lifeblood.test', '01711000006', 'A-', 'Sylhet', 'Sylhet Sadar, Sylhet', 'Female'],
            ['Imran Hossain', 'imran.hossain@demo.lifeblood.test', '01711000007', 'B-', 'Rajshahi', 'Boalia, Rajshahi', 'Male'],
            ['Farzana Akter', 'farzana.akter@demo.lifeblood.test', '01711000008', 'O+', 'Khulna', 'Sonadanga, Khulna', 'Female'],
            ['Arif Mahmud', 'arif.mahmud@demo.lifeblood.test', '01711000009', 'AB-', 'Narayanganj', 'Narayanganj Sadar, Narayanganj', 'Male'],
            ['Sumaiya Rahman', 'sumaiya.rahman@demo.lifeblood.test', '01711000010', 'A+', 'Mymensingh', 'Mymensingh Sadar, Mymensingh', 'Female'],
            ['Shakil Khan', 'shakil.khan@demo.lifeblood.test', '01711000011', 'B+', 'Rangpur', 'Rangpur Sadar, Rangpur', 'Male'],
            ['Tania Islam', 'tania.islam@demo.lifeblood.test', '01711000012', 'O-', 'Barishal', 'Barishal Sadar, Barishal', 'Female'],
        ];

        $donors = [];
        foreach ($donorData as $index => [$name, $email, $phone, $group, $city, $address, $gender]) {
            $user = User::updateOrCreate(['email' => $email], [
                'name' => $name,
                'password' => Hash::make('Donor@12345'),
                'role' => 'donor',
                'phone' => $phone,
                'is_active' => true,
            ]);

            $profile = DonorProfile::updateOrCreate(['user_id' => $user->id], [
                'blood_group' => $group,
                'date_of_birth' => now()->subYears(23 + ($index % 9))->subDays($index * 11)->toDateString(),
                'gender' => $gender,
                'address' => $address,
                'city' => $city,
                'last_donation_date' => now()->subDays(35 + ($index * 17))->toDateString(),
                'eligible' => true,
                'emergency_contact' => '0181100' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
            ]);

            $donors[$name] = [$user, $profile];
        }

        $hospitalData = [
            ['Evercare Hospital Dhaka', 'HOSP-DHK-001', 'Dr. Mahmud Rahman', 'Bashundhara R/A, Dhaka', 'Dhaka', '01713000001'],
            ['Square Hospitals Ltd.', 'HOSP-DHK-002', 'Nadia Sultana', 'Panthapath, Dhaka', 'Dhaka', '01713000002'],
            ['Chattogram Medical Centre', 'HOSP-CTG-001', 'Dr. Farhan Kabir', 'Panchlaish, Chattogram', 'Chattogram', '01713000003'],
            ['Rajshahi Central Hospital', 'HOSP-RAJ-001', 'Mizanur Rahman', 'Laxmipur, Rajshahi', 'Rajshahi', '01713000004'],
            ['Sylhet City Medical', 'HOSP-SYL-001', 'Dr. Samira Haque', 'Zindabazar, Sylhet', 'Sylhet', '01713000005'],
            ['Khulna Specialized Hospital', 'HOSP-KHL-001', 'Rafiq Ahmed', 'Sonadanga, Khulna', 'Khulna', '01713000006'],
        ];

        $hospitals = [];
        foreach ($hospitalData as $index => [$name, $registration, $contact, $address, $city, $phone]) {
            $email = 'demo.hospital' . ($index + 1) . '@lifeblood.test';
            $hospitalUser = User::updateOrCreate(['email' => $email], [
                'name' => $contact,
                'password' => Hash::make('Hospital@12345'),
                'role' => 'hospital',
                'phone' => $phone,
                'is_active' => true,
            ]);

            $hospital = Hospital::updateOrCreate(['registration_no' => $registration], [
                'user_id' => $hospitalUser->id,
                'hospital_name' => $name,
                'contact_person' => $contact,
                'address' => $address,
                'city' => $city,
                'phone' => $phone,
                'status' => 'approved',
            ]);
            $hospitals[] = $hospital;
        }

        $inventory = [
            [$hospitals[0], 'A+', 18, 'Main Blood Bank - Rack A1', 30],
            [$hospitals[0], 'A-', 7, 'Main Blood Bank - Rack A2', 25],
            [$hospitals[0], 'B+', 22, 'Main Blood Bank - Rack B1', 32],
            [$hospitals[0], 'B-', 6, 'Main Blood Bank - Rack B2', 28],
            [$hospitals[1], 'AB+', 9, 'Emergency Storage - Shelf C1', 24],
            [$hospitals[1], 'AB-', 4, 'Emergency Storage - Shelf C2', 21],
            [$hospitals[1], 'O+', 26, 'Main Blood Bank - Rack O1', 36],
            [$hospitals[1], 'O-', 8, 'Main Blood Bank - Rack O2', 29],
            [$hospitals[2], 'A+', 11, 'Chattogram Storage - A1', 27],
            [$hospitals[2], 'B+', 14, 'Chattogram Storage - B1', 31],
            [$hospitals[3], 'O+', 17, 'Rajshahi Storage - O1', 34],
            [$hospitals[3], 'O-', 5, 'Rajshahi Storage - O2', 22],
            [$hospitals[4], 'AB+', 6, 'Sylhet Storage - C1', 26],
            [$hospitals[4], 'B-', 5, 'Sylhet Storage - B2', 23],
            [$hospitals[5], 'A-', 8, 'Khulna Storage - A2', 20],
            [$hospitals[5], 'O+', 13, 'Khulna Storage - O1', 33],
        ];

        foreach ($inventory as [$hospital, $group, $units, $location, $days]) {
            BloodInventory::updateOrCreate(
                ['hospital_id' => $hospital->id, 'blood_group' => $group, 'storage_location' => $location],
                [
                    'units' => $units,
                    'expiry_date' => now()->addDays($days)->toDateString(),
                    'status' => 'available',
                ]
            );
        }

        $donationData = [
            ['Rahim Uddin', 'O+', 1, 4, 'City Blood Donation Camp'],
            ['Nusrat Jahan', 'A+', 1, 9, 'Square Hospital Donation Unit'],
            ['Tanvir Hasan', 'B+', 1, 15, 'Gazipur Community Blood Camp'],
            ['Mou Sultana', 'AB+', 1, 22, 'Chattogram Medical Centre'],
            ['Sabbir Ahmed', 'O-', 1, 31, 'Cumilla Blood Donation Camp'],
            ['Jannatul Ferdous', 'A-', 1, 38, 'Sylhet City Medical'],
            ['Imran Hossain', 'B-', 1, 45, 'Rajshahi Central Hospital'],
            ['Farzana Akter', 'O+', 1, 52, 'Khulna Specialized Hospital'],
            ['Arif Mahmud', 'AB-', 1, 61, 'Dhaka Volunteer Blood Camp'],
            ['Sumaiya Rahman', 'A+', 1, 70, 'Mymensingh Blood Drive'],
            ['Shakil Khan', 'B+', 1, 82, 'Rangpur Community Camp'],
            ['Tania Islam', 'O-', 1, 94, 'Barishal Blood Donation Camp'],
        ];

        foreach ($donationData as [$donorName, $group, $units, $daysAgo, $location]) {
            [$user] = $donors[$donorName];
            BloodDonation::updateOrCreate(
                ['donor_id' => $user->id, 'donated_at' => now()->subDays($daysAgo)->toDateString(), 'notes' => 'Demo donation | ' . $location],
                ['blood_group' => $group, 'units' => $units, 'screening_status' => 'approved']
            );
        }

        $requests = [
            [$hospitals[0], 'Ayesha Rahman', 28, 'O+', 2, 'critical', 1, 'Emergency surgery', 'approved'],
            [$hospitals[1], 'Sakib Hasan', 41, 'A+', 3, 'urgent', 2, 'Post-operative transfusion', 'pending'],
            [$hospitals[2], 'Mim Akter', 19, 'B+', 2, 'urgent', 3, 'Accident treatment', 'approved'],
            [$hospitals[3], 'Karim Uddin', 56, 'O-', 1, 'critical', 1, 'Emergency care', 'fulfilled'],
            [$hospitals[4], 'Nabila Chowdhury', 33, 'AB+', 2, 'normal', 5, 'Scheduled treatment', 'pending'],
            [$hospitals[5], 'Rashedul Islam', 47, 'A-', 2, 'urgent', 2, 'Medical procedure', 'approved'],
        ];

        foreach ($requests as [$hospital, $patient, $age, $group, $units, $urgency, $daysUntil, $reason, $status]) {
            BloodRequest::updateOrCreate(
                ['hospital_id' => $hospital->id, 'patient_name' => $patient],
                [
                    'patient_age' => $age,
                    'blood_group' => $group,
                    'units_required' => $units,
                    'urgency' => $urgency,
                    'needed_by' => now()->addDays($daysUntil)->setTime(10 + ($age % 6), 30),
                    'reason' => $reason,
                    'notes' => 'Demo request for presentation/testing data.',
                    'status' => $status,
                ]
            );
        }

        $appointments = [
            ['Rahim Uddin', 1, 'Evercare Hospital Dhaka', 'scheduled'],
            ['Nusrat Jahan', 2, 'Square Hospitals Ltd.', 'scheduled'],
            ['Tanvir Hasan', 3, 'Gazipur Community Blood Camp', 'completed'],
            ['Mou Sultana', 4, 'Chattogram Medical Centre', 'completed'],
            ['Farzana Akter', 5, 'Khulna Specialized Hospital', 'scheduled'],
            ['Jannatul Ferdous', 6, 'Sylhet City Medical', 'scheduled'],
        ];

        foreach ($appointments as [$donorName, $daysAhead, $location, $status]) {
            [$user] = $donors[$donorName];
            $appointmentAt = $status === 'completed'
                ? now()->subDays($daysAhead + 7)->setTime(11, 0)
                : now()->addDays($daysAhead)->setTime(11, 0);

            Appointment::updateOrCreate(
                ['donor_id' => $user->id, 'appointment_at' => $appointmentAt],
                ['location' => $location, 'status' => $status, 'notes' => 'Demo appointment for presentation/testing.']
            );
        }
    }
}

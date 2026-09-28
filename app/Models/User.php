<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'phone', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed', 'is_active' => 'boolean'];
    }

    public function donorProfile() { return $this->hasOne(DonorProfile::class); }
    public function hospital() { return $this->hasOne(Hospital::class); }
    public function donations() { return $this->hasMany(BloodDonation::class, 'donor_id'); }
    public function appointments() { return $this->hasMany(Appointment::class, 'donor_id'); }

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isDonor(): bool { return $this->role === 'donor'; }
    public function isHospital(): bool { return $this->role === 'hospital'; }
}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonorProfile extends Model
{
    protected $fillable = ['user_id','blood_group','date_of_birth','gender','address','city','last_donation_date','eligible','emergency_contact'];
    protected $casts = ['date_of_birth'=>'date','last_donation_date'=>'date','eligible'=>'boolean'];
    public function user() { return $this->belongsTo(User::class); }
}

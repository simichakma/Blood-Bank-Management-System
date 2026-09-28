<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    protected $fillable = ['user_id','hospital_name','registration_no','contact_person','address','city','phone','status'];
    public function user() { return $this->belongsTo(User::class); }
    public function requests() { return $this->hasMany(BloodRequest::class); }
}

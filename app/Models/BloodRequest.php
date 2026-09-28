<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodRequest extends Model
{
    protected $fillable = ['hospital_id','patient_name','patient_age','blood_group','units_required','urgency','needed_by','reason','notes','status'];
    protected $casts = ['needed_by'=>'datetime'];
    public function hospital() { return $this->belongsTo(Hospital::class); }
}

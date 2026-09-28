<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodInventory extends Model
{
    protected $table = 'blood_inventory';
    protected $fillable = ['hospital_id','blood_group','units','storage_location','expiry_date','status'];
    protected $casts = ['expiry_date'=>'date'];
    public function hospital() { return $this->belongsTo(Hospital::class); }
}

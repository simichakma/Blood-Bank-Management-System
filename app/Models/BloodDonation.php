<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodDonation extends Model
{
    protected $table = 'blood_donations';
    protected $fillable = ['donor_id','blood_group','units','donated_at','screening_status','notes'];
    protected $casts = ['donated_at'=>'date'];
    public function donor() { return $this->belongsTo(User::class, 'donor_id'); }
}

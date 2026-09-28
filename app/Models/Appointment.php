<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['donor_id','appointment_at','location','status','notes'];
    protected $casts = ['appointment_at'=>'datetime'];
    public function donor() { return $this->belongsTo(User::class, 'donor_id'); }
}

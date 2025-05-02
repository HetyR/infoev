<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LovedVehicle extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'vehicle_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Di dalam model User
    public function vehicles()
    {
        return $this->hasManyThrough(Vehicle::class, LovedVehicle::class);
    }
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }
}

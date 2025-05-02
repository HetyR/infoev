<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleView extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }

    public static function createViewLog($vehicle) {
        $vehicleViews = new VehicleView();
        $vehicleViews->vehicle_id = $vehicle->id;
        $vehicleViews->url = request()->url();
        $vehicleViews->session_id = request()->getSession()->getId();
        $vehicleViews->user_id = (auth()->check()) ? auth()->id() : null;
        $vehicleViews->ip = request()->ip();
        $vehicleViews->agent = request()->header('User-Agent');
        $vehicleViews->save();
    }
}

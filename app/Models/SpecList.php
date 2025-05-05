<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecList extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    // Relationship
    public function spec() {
        return $this->belongsTo(Spec::class);
    }

    public function specVehicles() {
        return $this->belongsToMany(SpecVehicle::class, 'spec_list_spec_vehicle', 'spec_list_id', 'spec_vehicle_id');
    }
}

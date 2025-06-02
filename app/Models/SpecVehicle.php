<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class SpecVehicle extends Pivot
{
    public $incrementing = true;

    public function lists() {
        return $this->belongsToMany(SpecList::class, 'spec_list_spec_vehicle', 'spec_vehicle_id', 'spec_list_id');
    }
    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spec extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $with = ['vehicles'];

    // Relationship
    public function specCategory() {
        return $this->belongsTo(SpecCategory::class);
    }

    public function vehicles() {
        return $this->belongsToMany(Vehicle::class)->withPivot('id', 'vehicle_id', 'spec_id', 'value', 'value_desc', 'value_bool')->using(SpecVehicle::class);
    }

    public function lists() {
        return $this->hasMany(SpecList::class);
    }
}

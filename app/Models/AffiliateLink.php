<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateLink extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    // Relationship
    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }

    public function marketplace() {
        return $this->belongsTo(Marketplace::class);
    }
}

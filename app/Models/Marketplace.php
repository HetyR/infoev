<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marketplace extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    // Relationship
    public function links() {
        return $this->hasMany(AffiliateLink::class);
    }

    public function logo() {
        return $this->morphOne(File::class, 'fileable');
    }
}

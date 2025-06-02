<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Vehicle extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $with = ['pictures', 'brand'];

    protected static function boot()
    {
        parent::boot();

        // Menambahkan slug setelah proses insert
        static::created(function ($vehicle) {
            $slug = $vehicle->brand->name . ' ' . $vehicle->name;
            $vehicle->slug = $vehicle->createSlug($slug, $vehicle->name);
            $vehicle->saveQuietly();
        });

        // Update slug setelah proses update
        static::updated(function ($vehicle) {
            $slug = $vehicle->brand->name . ' ' . $vehicle->name;
            $vehicle->slug = $vehicle->createSlug($slug, $vehicle->name);
            $vehicle->saveQuietly();
        });
    }

    // Menambahkan slug
    private function createSlug($slug, $name)
    {
        if (static::whereSlug($slug = Str::slug($slug))->exists()) {
            $count = static::whereName($name)->count();

            if ($count == 2) {
                return "{$slug}-2";
            }

            $slug = static::whereName($name)->latest('id')->skip(1)->value('slug');
            return preg_replace_callback('/(\d+)$/', function ($matches) {
                return $matches[1] + 1;
            }, $slug);
        }

        return $slug;
    }

    // Menggunakan slug untuk model binding
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Search
    public function scopeSearch($query, $q)
    {
        $query->where('name', 'like', '%' . $q . '%')
            ->orWhereRelation('brand', 'name', 'like', '%' . $q . '%');
    }

    // Relationship
    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function affiliateLinks()
    {
        return $this->hasMany(AffiliateLink::class);
    }


    public function specs()
    {
        return $this->belongsToMany(Spec::class)->withPivot('id', 'vehicle_id', 'spec_id', 'value', 'value_desc', 'value_bool')->using(SpecVehicle::class);
    }

    public function pictures()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function views()
    {
        return $this->hasMany(VehicleView::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function countView()
    {
        if (auth()->id() == null) {
            return $this->views()
                ->where('ip', '=',  request()->ip())
                ->where('session_id', '=', request()->getSession()->getId())
                ->doesntExist();
        }

        return $this->views()
            ->where(function ($query) {
                $query
                    ->where('session_id', '=', request()->getSession()->getId())
                    ->orWhere('user_id', '=', (auth()->check()));
            })->doesntExist();
    }

    public function wishlistedBy()
    {
        return $this->belongsToMany(User::class, 'wishlist')->withTimestamps();
    }

    public function getIsInWishlistAttribute()
    {
        return $this->wishlistedBy()->where('user_id', auth()->id())->exists();
    }

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'loved_vehicles');
    }


    public function lovedVehicles()
    {
        return $this->hasMany(LovedVehicle::class);
    }

    public function affiliate()
    {
        return $this->hasMany(AffiliateLink::class, 'vehicle_id');
    }

}


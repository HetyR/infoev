<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Type extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected static function boot() {
        parent::boot();

        // Menambahkan slug saat proses insert
        static::created(function ($type) {
            $type->slug = $type->createSlug($type->name);
            $type->save();
        });
    }

    // Menambahkan slug
    private function createSlug($name) {
        if (static::whereSlug($slug = Str::slug($name))->exists()) {
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
    public function getRouteKeyName() {
        return 'slug';
    }

    // Relationship
    public function vehicles() {
        return $this->hasMany(Vehicle::class);
    }

    public function thumbnail() {
        return $this->morphOne(File::class, 'fileable');
    }

    
}

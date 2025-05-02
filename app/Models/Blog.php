<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $with = ['thumbnail'];

    protected static function boot() {
        parent::boot();

        // Menambahkan slug saat proses insert
        static::created(function ($blog) {
            $blog->slug = $blog->createSlug($blog->title);
            $blog->save();
        });
    }

    // Menambahkan slug
    private function createSlug($title) {
        if (static::whereSlug($slug = Str::slug($title))->exists()) {
            $count = static::whereTitle($title)->count();

            if ($count == 2) {
                return "{$slug}-2";
            }

            $slug = static::whereTitle($title)->latest('id')->skip(1)->value('slug');
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

    // Filter search
    public function scopeSearch($query, $q) {
        if ($q ?? false) {
            $query->where('title', 'like', '%' . $q . '%');
        }
    }

    // Relationship
    public function thumbnail() {
        return $this->morphOne(File::class, 'fileable');
    }

    public function sticky() {
        return $this->hasOne(StickyArticle::class);
    }

    public function comments() {
        return $this->morphMany(Comment::class, 'commentable');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StickyArticle extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    // Relationship
<<<<<<< HEAD
 public function blog()
{
    return $this->belongsTo(Blog::class, 'blog_id');
=======
    public function blog()
    {
        return $this->belongsTo(Blog::class, 'blog_id', 'id');
    }
>>>>>>> b73c9d4acb6ccc9124e2e0d0344bdb8963f510e3
}

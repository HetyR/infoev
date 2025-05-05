<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Option;
use App\Models\Vehicle;
class BlogController extends Controller
{
 
 
    
        public function index(Request $request) {
            $getImageUrl = function ($image) {
                return $image ? asset('storage/' . $image->path) : null;
            };
        
          
            $banner = Option::where([
                ['type', 'banner'],
                ['name', 'blog']
            ])->with('thumbnail')->first();
            
            $banner_url = is_null($banner) || is_null($banner->thumbnail) ? null : $getImageUrl($banner->thumbnail);
        
           
            $posts = Blog::with('thumbnail')
                        ->latest()
                        ->where('published', true)
                        ->search($request->q)
                        ->paginate(15)
                        ->through(function ($post) use ($getImageUrl) {
                            $post->thumbnail_url = $getImageUrl($post->thumbnail);
                            return $post;
                        });
        
        
        
            return response()->json([
              
                'posts' => $posts,
                
            ]);
        }
        
    
        public function show(Blog $blog) {
            $stickies = Blog::with('thumbnail')
                            ->select('sticky_articles.*', 'blogs.*')
                            ->join('sticky_articles', 'blogs.id', '=', 'sticky_articles.blog_id')
                            ->where('blogs.published', true)
                            ->orderBy('sticky_articles.created_at', 'desc')
                            ->get();
            $featured = Blog::with('thumbnail')
                            ->latest()
                            ->where('published', true)
                            ->where('featured', true)
                            ->limit(3)
                            ->get();
            $newsLimit = 3 - $featured->count();
            if ($newsLimit > 0 && $newsLimit <= 3) {
                $remainderArticles = Blog::with('thumbnail')->latest()->where('published', true)->limit($newsLimit)->get();
                $stickies = $stickies->concat($featured)->concat($remainderArticles);
            }
    
            return view('blog.show', [
              
                'post' => $blog,
                'stickies' => $stickies,
                
            ]);
        }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
 
}

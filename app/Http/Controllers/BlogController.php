<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Option;
use App\Models\Vehicle;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request) {
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

        $banner = Option::where([
            ['type', 'banner'],
            ['name', 'blog']
        ])->with('thumbnail')->first();

        return view('blog.index', [
            'bikeBrands' => Brand::limit(14)
                            ->whereHas('vehicles.type', function (Builder $query) {
                                $query->where('name', 'sepeda motor');
                            })
                            ->withCount('vehicles')
                            ->having('vehicles_count', '>', 0)
                            ->orderBy('vehicles_count', 'desc')
                            ->get(),
            'carBrands' => Brand::limit(14)
                            ->whereHas('vehicles.type', function (Builder $query) {
                                $query->where('name', 'mobil');
                            })
                            ->withCount('vehicles')
                            ->having('vehicles_count', '>', 0)
                            ->orderBy('vehicles_count', 'desc')
                            ->get(),
            'posts' => Blog::with('thumbnail')
                        ->latest()
                        ->where('published', true)
                        ->search($request->q)
                        ->paginate(15),
            'banner' => is_null($banner) || is_null($banner->thumbnail)  ? null : $banner->thumbnail,
            'stickies' => $stickies,
            'recentVehicles' => Vehicle::with('brand')->latest()->limit(8)->get(),
            'popularVehicles' => Vehicle::with('brand')
                ->whereHas('views', fn (Builder $query) => $query->where('created_at', '>', now()->subMonths(3)))
                ->withCount('views')
                ->orderBy('views_count', 'desc')
                ->limit(10)
                ->get(),
            'logo' => Option::where('type', 'logo')->with('thumbnail')->first()
        ]);
    }


    // public function index(Request $request) {
    //     $getImageUrl = function ($image) {
    //         return $image ? asset('storage/' . $image->path) : null;
    //     };
    
    //     $stickies = Blog::with('thumbnail')
    //                     ->select('sticky_articles.*', 'blogs.*')
    //                     ->join('sticky_articles', 'blogs.id', '=', 'sticky_articles.blog_id')
    //                     ->where('blogs.published', true)
    //                     ->orderBy('sticky_articles.created_at', 'desc')
    //                     ->get()
    //                     ->map(function ($sticky) use ($getImageUrl) {
    //                         $sticky->thumbnail_url = $getImageUrl($sticky->thumbnail);
    //                         return $sticky;
    //                     });
    
    //     $featured = Blog::with('thumbnail')
    //                     ->latest()
    //                     ->where('published', true)
    //                     ->where('featured', true)
    //                     ->limit(3)
    //                     ->get()
    //                     ->map(function ($feature) use ($getImageUrl) {
    //                         $feature->thumbnail_url = $getImageUrl($feature->thumbnail);
    //                         return $feature;
    //                     });
    
    //     $newsLimit = 3 - $featured->count();
    //     if ($newsLimit > 0 && $newsLimit <= 3) {
    //         $remainderArticles = Blog::with('thumbnail')
    //                                 ->latest()
    //                                 ->where('published', true)
    //                                 ->limit($newsLimit)
    //                                 ->get()
    //                                 ->map(function ($article) use ($getImageUrl) {
    //                                     $article->thumbnail_url = $getImageUrl($article->thumbnail);
    //                                     return $article;
    //                                 });
    //         $stickies = $stickies->concat($featured)->concat($remainderArticles);
    //     }
    
    //     $banner = Option::where([
    //         ['type', 'banner'],
    //         ['name', 'blog']
    //     ])->with('thumbnail')->first();
        
    //     $banner_url = is_null($banner) || is_null($banner->thumbnail) ? null : $getImageUrl($banner->thumbnail);
    
    //     $bikeBrands = Brand::limit(14)
    //                     ->whereHas('vehicles.type', function (Builder $query) {
    //                         $query->where('name', 'sepeda motor');
    //                     })
    //                     ->withCount('vehicles')
    //                     ->having('vehicles_count', '>', 0)
    //                     ->orderBy('vehicles_count', 'desc')
    //                     ->get();
    
    //     $carBrands = Brand::limit(14)
    //                     ->whereHas('vehicles.type', function (Builder $query) {
    //                         $query->where('name', 'mobil');
    //                     })
    //                     ->withCount('vehicles')
    //                     ->having('vehicles_count', '>', 0)
    //                     ->orderBy('vehicles_count', 'desc')
    //                     ->get();
    
    //     $posts = Blog::with('thumbnail')
    //                 ->latest()
    //                 ->where('published', true)
    //                 ->search($request->q)
    //                 ->paginate(15)
    //                 ->through(function ($post) use ($getImageUrl) {
    //                     $post->thumbnail_url = $getImageUrl($post->thumbnail);
    //                     return $post;
    //                 });
    
    //     $recentVehicles = Vehicle::with('brand')
    //                             ->latest()
    //                             ->limit(8)
    //                             ->get();
    
    //     $popularVehicles = Vehicle::with('brand')
    //                             ->whereHas('views', fn (Builder $query) => $query->where('created_at', '>', now()->subMonths(3)))
    //                             ->withCount('views')
    //                             ->orderBy('views_count', 'desc')
    //                             ->limit(10)
    //                             ->get();
    
    //     $logo = Option::where('type', 'logo')->with('thumbnail')->first();
    //     $logo_url = $logo ? $getImageUrl($logo->thumbnail) : null;
    
    //     return response()->json([
    //         'bikeBrands' => $bikeBrands,
    //         'carBrands' => $carBrands,
    //         'posts' => $posts,
    //         'banner' => $banner_url,
    //         'stickies' => $stickies,
    //         'recentVehicles' => $recentVehicles,
    //         'popularVehicles' => $popularVehicles,
    //         'logo' => $logo_url,
    //     ]);
    // }
    

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
            'bikeBrands' => Brand::limit(14)
                            ->whereHas('vehicles.type', function (Builder $query) {
                                $query->where('name', 'sepeda motor');
                            })
                            ->withCount('vehicles')
                            ->having('vehicles_count', '>', 0)
                            ->orderBy('vehicles_count', 'desc')
                            ->get(),
            'carBrands' => Brand::limit(14)
                            ->whereHas('vehicles.type', function (Builder $query) {
                                $query->where('name', 'mobil');
                            })
                            ->withCount('vehicles')
                            ->having('vehicles_count', '>', 0)
                            ->orderBy('vehicles_count', 'desc')
                            ->get(),
            'post' => $blog,
            'stickies' => $stickies,
            'recentVehicles' => Vehicle::with('brand')->latest()->limit(8)->get(),
            'popularVehicles' => Vehicle::with('brand')
                ->whereHas('views', fn (Builder $query) => $query->where('created_at', '>', now()->subMonths(3)))
                ->withCount('views')
                ->orderBy('views_count', 'desc')
                ->limit(10)
                ->get(),
            'logo' => Option::where('type', 'logo')->with('thumbnail')->first()
        ]);
    }
}

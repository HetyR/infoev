<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Option;
use App\Models\Spec;
use App\Models\Vehicle;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index() {
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

        return view('home.index', [
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
            'posts' => Blog::with('thumbnail')->latest()->where('published', true)->limit(15)->get(),
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

    // public function index() {
    //     $getImageUrl = function ($image) {
    //         return $image ? asset('storage/' . $image->path) : null;
    //     };
    
    //     $stickies = Blog::with('thumbnail')
    //                     ->select('sticky_articles.*', 'blogs.*')
    //                     ->join('sticky_articles', 'blogs.id', '=', 'sticky_articles.blog_id')
    //                     ->where('blogs.published', true)
    //                     ->orderBy('sticky_articles.created_at', 'desc')
    //                     ->get()
    //                     ->each(function($item) use ($getImageUrl) {
    //                         $item->thumbnail_url = $getImageUrl($item->thumbnail);
    //                     });
    
    //     $featured = Blog::with('thumbnail')
    //                     ->latest()
    //                     ->where('published', true)
    //                     ->where('featured', true)
    //                     ->limit(3)
    //                     ->get()
    //                     ->each(function($item) use ($getImageUrl) {
    //                         $item->thumbnail_url = $getImageUrl($item->thumbnail);
    //                     });
    
    //     $newsLimit = 3 - $featured->count();
    //     if ($newsLimit > 0 && $newsLimit <= 3) {
    //         $remainderArticles = Blog::with('thumbnail')->latest()->where('published', true)->limit($newsLimit)->get()
    //             ->each(function($item) use ($getImageUrl) {
    //                 $item->thumbnail_url = $getImageUrl($item->thumbnail);
    //             });
    //         $stickies = $stickies->concat($featured)->concat($remainderArticles);
    //     }
    
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
    
    //     $posts = Blog::with('thumbnail')->latest()->where('published', true)->limit(15)->get()
    //         ->each(function($item) use ($getImageUrl) {
    //             $item->thumbnail_url = $getImageUrl($item->thumbnail);
    //         });
    
    //     $recentVehicles = Vehicle::with('brand')->latest()->limit(8)->get()
    //         ->each(function($item) use ($getImageUrl) {
    //             $item->brand_logo_url = $getImageUrl($item->brand->logo);
    //         });
    
    //     $popularVehicles = Vehicle::with('brand')
    //                         ->whereHas('views', fn (Builder $query) => $query->where('created_at', '>', now()->subMonths(3)))
    //                         ->withCount('views')
    //                         ->orderBy('views_count', 'desc')
    //                         ->limit(10)
    //                         ->get()
    //                         ->each(function($item) use ($getImageUrl) {
    //                             $item->brand_logo_url = $getImageUrl($item->brand->logo);
    //                         });
    
    //     $logo = Option::where('type', 'logo')->with('thumbnail')->first();
    //     $logo_url = $getImageUrl($logo->thumbnail);
    
    //     return response()->json([
    //         'bikeBrands' => $bikeBrands,
    //         'carBrands' => $carBrands,
    //         'posts' => $posts,
    //         'stickies' => $stickies,
    //         'recentVehicles' => $recentVehicles,
    //         'popularVehicles' => $popularVehicles,
    //         'logo' => $logo_url
    //     ]);
    // }
    
    

    
    public function search(Request $request) {
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

        $searchVehicleIds = Vehicle::search($request->q)->get()->pluck('id');
        $vehicles = Spec::find(1)
                        ->vehicles()
                        ->wherePivotIn('vehicle_id', $searchVehicleIds)
                        ->orderByPivot('value', 'desc')
                        ->paginate(15);

        return view('vehicle.index', [
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
            'vehicles' => $vehicles,
            'title' => 'Hasil Pencarian',
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

    // public function search(Request $request) {
    //     $getImageUrl = function ($image) {
    //         return $image ? asset('storage/' . $image->path) : null;
    //     };
    //     $stickies = Blog::with('thumbnail')
    //                     ->select('sticky_articles.*', 'blogs.*')
    //                     ->join('sticky_articles', 'blogs.id', '=', 'sticky_articles.blog_id')
    //                     ->where('blogs.published', true)
    //                     ->orderBy('sticky_articles.created_at', 'desc')
    //                     ->get();
    //     $featured = Blog::with('thumbnail')
    //                     ->latest()
    //                     ->where('published', true)
    //                     ->where('featured', true)
    //                     ->limit(3)
    //                     ->get();
    //     $newsLimit = 3 - $featured->count();
    //     if ($newsLimit > 0 && $newsLimit <= 3) {
    //         $remainderArticles = Blog::with('thumbnail')->latest()->where('published', true)->limit($newsLimit)->get();
    //         $stickies = $stickies->concat($featured)->concat($remainderArticles);
    //     }
    
    //     $searchVehicleIds = Vehicle::search($request->q)->get()->pluck('id');
    //     $vehicles = Spec::find(1)
    //                     ->vehicles()
    //                     ->wherePivotIn('vehicle_id', $searchVehicleIds)
    //                     ->orderByPivot('value', 'desc')
    //                     ->paginate(15);
    //                     $vehicles->each(function($vehicle) use ($getImageUrl) {
    //                         $firstPicture = $vehicle->pictures->first();
    //                         $vehicle->thumbnail_url = $firstPicture ? $getImageUrl($firstPicture) : null;
    //                     });
                    
    //     return response()->json([
    //         'bikeBrands' => Brand::limit(14)
    //                         ->whereHas('vehicles.type', function (Builder $query) {
    //                             $query->where('name', 'sepeda motor');
    //                         })
    //                         ->withCount('vehicles')
    //                         ->having('vehicles_count', '>', 0)
    //                         ->orderBy('vehicles_count', 'desc')
    //                         ->get(),
    //         'carBrands' => Brand::limit(14)
    //                         ->whereHas('vehicles.type', function (Builder $query) {
    //                             $query->where('name', 'mobil');
    //                         })
    //                         ->withCount('vehicles')
    //                         ->having('vehicles_count', '>', 0)
    //                         ->orderBy('vehicles_count', 'desc')
    //                         ->get(),
    //         'vehicles' => $vehicles,
    //         'title' => 'Hasil Pencarian',
    //         'stickies' => $stickies,
    //         'recentVehicles' => Vehicle::with('brand')->latest()->limit(8)->get(),
    //         'popularVehicles' => Vehicle::with('brand')
    //             ->whereHas('views', fn (Builder $query) => $query->where('created_at', '>', now()->subMonths(3)))
    //             ->withCount('views')
    //             ->orderBy('views_count', 'desc')
    //             ->limit(10)
    //             ->get(),
    //         'logo' => Option::where('type', 'logo')->with('thumbnail')->first()
    //     ]);
    // }
    
}

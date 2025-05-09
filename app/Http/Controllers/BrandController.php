<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Type;
use App\Models\Brand;
use App\Models\Option;
use App\Models\Spec;
use App\Models\Vehicle;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BrandController extends Controller
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
            ['name', 'brand']
        ])->with('thumbnail')->first();

        // Ambil data tipe untuk filter
        $types = Type::all(); // Mengambil semua tipe yang ada

        // Jika ada filter tipe, ambil brand berdasarkan tipe yang dipilih
        $brandsQuery = Brand::orderBy('name')->withCount('vehicles')->having('vehicles_count', '>', 0);
        
        // Cek apakah ada filter tipe
        if ($request->has('type') && $request->type) {
            // Perbaiki bagian filter dengan menggunakan relasi yang tepat
            $brandsQuery->whereHas('vehicles', function ($query) use ($request) {
                $query->where('type_id', $request->type);
            });
        }

        // Menjalankan query dan mengambil hasilnya
        $brands = $brandsQuery->get();

        return view('brand.index', [
            'items' => $brands,
            'types' => $types, // Kirimkan data tipe ke view
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



    // public function index() {
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
    
    //     $banner = Option::where([
    //         ['type', 'banner'],
    //         ['name', 'brand']
    //     ])->with('thumbnail')->first();
    
    //     $items = Brand::orderBy('name')
    //                   ->withCount('vehicles')
    //                   ->having('vehicles_count', '>', 0)
    //                   ->get();
    
    //     $recentVehicles = Vehicle::with('brand')
    //                              ->latest()
    //                              ->limit(8)
    //                              ->get();
    
    //     $popularVehicles = Vehicle::with('brand')
    //                               ->whereHas('views', function (Builder $query) {
    //                                   $query->where('created_at', '>', now()->subMonths(3));
    //                               })
    //                               ->withCount('views')
    //                               ->orderBy('views_count', 'desc')
    //                               ->limit(10)
    //                               ->get();
    
    //     $logo = Option::where('type', 'logo')
    //                   ->with('thumbnail')
    //                   ->first();
    
    //     return response()->json([
    //         'items' => $items,
    //         'banner' => is_null($banner) || is_null($banner->thumbnail) ? null : $banner->thumbnail,
    //         'stickies' => $stickies,
    //         'recentVehicles' => $recentVehicles,
    //         'popularVehicles' => $popularVehicles,
    //         'logo' => $logo
    //     ]);
    // }
    

    public function show(Brand $brand) {
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
            'vehicles' => Spec::find(1)
                            ->vehicles()
                            ->where('brand_id', $brand->id)
                            ->orderByPivot('value', 'desc')
                            ->paginate(15),
            'title' => $brand->name,
            'banner' => $brand->thumbnail,
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
    // public function show(Brand $brand) {
    //     // Helper function to generate full URL for images
    //     $getImageUrl = function ($image) {
    //         return $image ? asset('storage/' . $image->path) : null;
    //     };
    
    //     // Get stickies with thumbnail
    //     $stickies = Blog::with('thumbnail')
    //                     ->select('sticky_articles.*', 'blogs.*')
    //                     ->join('sticky_articles', 'blogs.id', '=', 'sticky_articles.blog_id')
    //                     ->where('blogs.published', true)
    //                     ->orderBy('sticky_articles.created_at', 'desc')
    //                     ->get();
    
    //     // Get featured blogs
    //     $featured = Blog::with('thumbnail')
    //                     ->latest()
    //                     ->where('published', true)
    //                     ->where('featured', true)
    //                     ->limit(3)
    //                     ->get();
    
    //     // Calculate how many more articles we need to fill the news section
    //     $newsLimit = 3 - $featured->count();
    //     if ($newsLimit > 0 && $newsLimit <= 3) {
    //         $remainderArticles = Blog::with('thumbnail')
    //                                 ->latest()
    //                                 ->where('published', true)
    //                                 ->limit($newsLimit)
    //                                 ->get();
    //         $stickies = $stickies->concat($featured)->concat($remainderArticles);
    //     }
    
    //     // Get bike brands with vehicle count
    //     $bikeBrands = Brand::limit(14)
    //                        ->whereHas('vehicles.type', function (Builder $query) {
    //                            $query->where('name', 'sepeda motor');
    //                        })
    //                        ->withCount('vehicles')
    //                        ->having('vehicles_count', '>', 0)
    //                        ->orderBy('vehicles_count', 'desc')
    //                        ->get();
    
    //     // Get car brands with vehicle count
    //     $carBrands = Brand::limit(14)
    //                       ->whereHas('vehicles.type', function (Builder $query) {
    //                           $query->where('name', 'mobil');
    //                       })
    //                       ->withCount('vehicles')
    //                       ->having('vehicles_count', '>', 0)
    //                       ->orderBy('vehicles_count', 'desc')
    //                       ->get();
    
    //     // Get vehicles for the specified brand
    //     $vehicles = Spec::find(1)
    //                     ->vehicles()
    //                     ->where('brand_id', $brand->id)
    //                     ->wherePivot('spec_id', 1) // Filter berdasarkan spec_id
    //                     ->orderByPivot('value', 'desc') // Urutkan berdasarkan value secara descending
    //                     ->paginate(15);
    
    
    //     // Get recent vehicles
    //     $recentVehicles = Vehicle::with('brand')
    //                              ->latest()
    //                              ->limit(8)
    //                              ->get();
    
    //     // Get popular vehicles based on views in the last 3 months
    //     $popularVehicles = Vehicle::with('brand')
    //                               ->whereHas('views', function (Builder $query) {
    //                                   $query->where('created_at', '>', now()->subMonths(3));
    //                               })
    //                               ->withCount('views')
    //                               ->orderBy('views_count', 'desc')
    //                               ->limit(10)
    //                               ->get();
    
    //     // Get logo with thumbnail
    //     $logo = Option::where('type', 'logo')
    //                   ->with('thumbnail')
    //                   ->first();
    
    //     // Construct banner URL
    //     $bannerUrl = $getImageUrl($brand->thumbnail);
    
    //     // Ensure image URLs for blogs and vehicles
    //     $stickies->each(function($blog) use ($getImageUrl) {
    //         $blog->thumbnail_url = $getImageUrl($blog->thumbnail);
    //     });
    
    //     $featured->each(function($blog) use ($getImageUrl) {
    //         $blog->thumbnail_url = $getImageUrl($blog->thumbnail);
    //     });
    //     $vehicles->each(function($vehicle) use ($getImageUrl) {
    //         $firstPicture = $vehicle->pictures->first();
    //         $vehicle->thumbnail_url = $firstPicture ? $getImageUrl($firstPicture) : null;
    //     });
    //     $recentVehicles->each(function($vehicle) use ($getImageUrl) {
    //         $firstPicture = $vehicle->pictures->first();
    //         $vehicle->thumbnail_url = $firstPicture ? $getImageUrl($firstPicture) : null;
    //     });
        
    //     $popularVehicles->each(function($vehicle) use ($getImageUrl) {
    //         $firstPicture = $vehicle->pictures->first();
    //         $vehicle->thumbnail_url = $firstPicture ? $getImageUrl($firstPicture) : null;
    //     });
        
       
    
    //     return response()->json([
    //         'bikeBrands' => $bikeBrands,
    //         'carBrands' => $carBrands,
    //         'vehicles' => $vehicles,
    //         'title' => $brand->name,
    //         'banner' => $bannerUrl,
    //         'stickies' => $stickies,
    //         'recentVehicles' => $recentVehicles,
    //         'popularVehicles' => $popularVehicles,
    //         'logo' => $logo
    //     ]);
    // }
    
    
}

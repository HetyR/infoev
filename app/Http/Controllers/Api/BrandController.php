<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Option;
use App\Models\Spec;
use App\Models\Vehicle;
use Illuminate\Contracts\Database\Eloquent\Builder;

class BrandController extends Controller
{
    public function index() {
      
    
        $items = Brand::orderBy('name')
                      ->withCount('vehicles')
                      ->having('vehicles_count', '>', 0)
                      ->get();
    
       
    
        return response()->json([
            'items' => $items,
          
        ]);
    }
    

    // public function show(Brand $brand) {
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

    //     return view('vehicle.index', [
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
    //         'vehicles' => Spec::find(1)
    //                         ->vehicles()
    //                         ->where('brand_id', $brand->id)
    //                         ->orderByPivot('value', 'desc')
    //                         ->paginate(15),
    //         'title' => $brand->name,
    //         'banner' => $brand->thumbnail,
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
    public function show(Brand $brand)
    {
        // Helper function to generate full URL for images
        $getImageUrl = function ($image) {
            return $image ? asset('storage/' . $image->path) : null;
        };
    
        // Get vehicles for the specified brand
        $vehicles = Spec::find(1)
                        ->vehicles()
                        ->where('brand_id', $brand->id)
                        ->wherePivot('spec_id', 1) // Filter berdasarkan spec_id
                        ->orderByPivot('value', 'desc') // Urutkan berdasarkan value secara descending
                        ->get(); // Mengambil semua kendaraan tanpa pagination
    
        // Memproses setiap kendaraan untuk mendapatkan thumbnail, nama, slug, dan nilai spesifikasi
        $vehicles->transform(function($vehicle) use ($getImageUrl) {
            $firstPicture = $vehicle->pictures->first();
            $vehicle->thumbnail_url = $firstPicture ? $getImageUrl($firstPicture) : null;
    
            // Hanya ambil data yang diperlukan dari spesifikasi
            $vehicle->spec = [
                'spec_id' => $vehicle->pivot->spec_id,
                'value' => $vehicle->pivot->value,
            ];
    
            // Hapus data yang tidak diperlukan
            unset($vehicle->pictures);
            unset($vehicle->pivot);
    
            // Hanya menyimpan data yang diperlukan
            $vehicle = collect($vehicle)->only(['id', 'name', 'slug', 'thumbnail_url', 'spec']);
    
            return $vehicle;
        });
    
        // Construct banner URL
        $bannerUrl = $getImageUrl($brand->thumbnail);
    
        // Mengembalikan respons JSON dengan data yang diinginkan
        return response()->json([
            'vehicles' => $vehicles,
            'name_brand' => $brand->name,
            'banner' => $bannerUrl,
        ]);
    }
}    
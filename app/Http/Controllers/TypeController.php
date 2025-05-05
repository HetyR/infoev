<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Option;
use App\Models\Spec;
use App\Models\Type;
use App\Models\Vehicle;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TypeController extends Controller
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

        $banner = Option::where([
            ['type', 'banner'],
            ['name', 'type']
        ])->with('thumbnail')->first();

        return view('type.index', [
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
            'items' => Type::orderBy('name')->withCount('vehicles')->having('vehicles_count', '>', 0)->get(),
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
    //         ['name', 'type']
    //     ])->with('thumbnail')->first();
    
    //     $data = [
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
    //         'items' => Type::orderBy('name')->withCount('vehicles')->having('vehicles_count', '>', 0)->get(),
    //         'banner' => is_null($banner) || is_null($banner->thumbnail)  ? null : $banner->thumbnail,
    //         'stickies' => $stickies,
    //         'recentVehicles' => Vehicle::with('brand')->latest()->limit(8)->get(),
    //         'popularVehicles' => Vehicle::with('brand')
    //             ->whereHas('views', fn (Builder $query) => $query->where('created_at', '>', now()->subMonths(3)))
    //             ->withCount('views')
    //             ->orderBy('views_count', 'desc')
    //             ->limit(10)
    //             ->get(),
    //         'logo' => Option::where('type', 'logo')->with('thumbnail')->first()
    //     ];
    
    //     return response()->json($data);
    // }
    




    public function show(Type $type) {
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
                            ->where('type_id', $type->id)
                            ->orderByPivot('value', 'desc')
                            ->paginate(15),
            'title' => 'Daftar ' . $type->name . ' Listrik',
            'banner' => $type->thumbnail,
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


    // public function show(Type $type) {
      
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
    
    //     // Memuat gambar dari URL atas untuk setiap kendaraan
    //     $vehicles = Spec::find(1)
    //                     ->vehicles()
    //                     ->where('type_id', $type->id)
    //                     ->orderByPivot('value', 'desc')
    //                     ->paginate(15);
    
    //     $vehicles->each(function($vehicle) use ($getImageUrl) {
    //         $firstPicture = $vehicle->pictures->first();
    //         $vehicle->thumbnail_url = $firstPicture ? $getImageUrl($firstPicture) : null;
    //     });
    
    //     $data = [
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
    //         'title' => 'Daftar ' . $type->name . ' Listrik',
    //         'banner' => $type->thumbnail,
    //         'stickies' => $stickies,
    //         'recentVehicles' => Vehicle::with('brand')->latest()->limit(8)->get(),
    //         'popularVehicles' => Vehicle::with('brand')
    //             ->whereHas('views', fn (Builder $query) => $query->where('created_at', '>', now()->subMonths(3)))
    //             ->withCount('views')
    //             ->orderBy('views_count', 'desc')
    //             ->limit(10)
    //             ->get(),
    //         'logo' => Option::where('type', 'logo')->with('thumbnail')->first()
    //     ];
    
    //     return response()->json($data);
    // }
    
}

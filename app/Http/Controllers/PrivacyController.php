<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Option;
use App\Models\Brand;
use App\Models\Blog;
use App\Models\Vehicle;

class PrivacyController extends Controller
{
    //
    public function index()
    {
        $logo = Option::where('type', 'logo')->with('thumbnail')->first();
        $bikeBrands = Brand::limit(14)
            ->whereHas('vehicles.type', function (\Illuminate\Database\Eloquent\Builder $query) {
                $query->where('name', 'sepeda motor');
            })
            ->withCount('vehicles')
            ->having('vehicles_count', '>', 0)
            ->orderBy('vehicles_count', 'desc')
            ->get();
        $carBrands = Brand::limit(14)
            ->whereHas('vehicles.type', function (\Illuminate\Database\Eloquent\Builder $query) {
                $query->where('name', 'mobil');
            })
            ->withCount('vehicles')
            ->having('vehicles_count', '>', 0)
            ->orderBy('vehicles_count', 'desc')
            ->get();


        // Define the $banner variable
        $banner = Option::where('type', 'banner')->with('thumbnail')->first();

        // Define the $stickies variable
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
        $vehicles = Vehicle::all(); // Ambil semua data kendaraan

        return view('privacy.index', [

            'logo' => $logo,
            'bikeBrands' => $bikeBrands,
            'carBrands' => $carBrands,
            'posts' => Blog::with('thumbnail')
                ->latest()
                ->where('published', true)
                ->paginate(15),
            'banner' => is_null($banner) || is_null($banner->thumbnail)  ? null : $banner->thumbnail,
            'recentVehicles' => Vehicle::with('brand')->latest()->limit(8)->get(),
            'popularVehicles' => Vehicle::with('brand')
                ->whereHas('views', fn (\Illuminate\Database\Eloquent\Builder $query) => $query->where('created_at', '>', now()->subMonths(3)))
                ->withCount('views')
                ->orderBy('views_count', 'desc')
                ->limit(10)
                ->get(),
            'stickies' => $stickies,
            'logo' => Option::where('type', 'logo')->with('thumbnail')->first(),
        ]);
    }

}

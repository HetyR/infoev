<?php

namespace App\Http\Controllers\Api;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Option;
use App\Models\Spec;
use App\Models\Vehicle;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getImageUrl = function ($path) {
            return $path ? asset('storage/' . $path) : null;
        };

        $stickies = Blog::with('thumbnail')
                        ->select('sticky_articles.*', 'blogs.*')
                        ->join('sticky_articles', 'blogs.id', '=', 'sticky_articles.blog_id')
                        ->where('blogs.published', true)
                        ->orderBy('sticky_articles.created_at', 'desc')
                        ->get()
                        ->each(function($item) use ($getImageUrl) {
                            $item->thumbnail_url = $getImageUrl($item->thumbnail->path ?? null);
                        });

        $featured = Blog::with('thumbnail')
                        ->latest()
                        ->where('published', true)
                        ->where('featured', true)
                        ->limit(3)
                        ->get()
                        ->each(function($item) use ($getImageUrl) {
                            $item->thumbnail_url = $getImageUrl($item->thumbnail->path ?? null);
                        });

        $newsLimit = 3 - $featured->count();
        if ($newsLimit > 0 && $newsLimit <= 3) {
            $remainderArticles = Blog::with('thumbnail')->latest()->where('published', true)->limit($newsLimit)->get()
                ->each(function($item) use ($getImageUrl) {
                    $item->thumbnail_url = $getImageUrl($item->thumbnail->path ?? null);
                });
            $stickies = $stickies->concat($featured)->concat($remainderArticles);
        }

        // $bikeBrands = Brand::limit(14)
        //                 ->whereHas('vehicles.type', function (Builder $query) {
        //                     $query->where('name', 'sepeda motor');
        //                 })
        //                 ->withCount('vehicles')
        //                 ->having('vehicles_count', '>', 0)
        //                 ->orderBy('vehicles_count', 'desc')
        //                 ->get();

        // $carBrands = Brand::limit(14)
        //                 ->whereHas('vehicles.type', function (Builder $query) {
        //                     $query->where('name', 'mobil');
        //                 })
        //                 ->withCount('vehicles')
        //                 ->having('vehicles_count', '>', 0)
        //                 ->orderBy('vehicles_count', 'desc')
        //                 ->get();

        $posts = Blog::with('thumbnail')->latest()->where('published', true)->limit(15)->get()
            ->each(function($item) use ($getImageUrl) {
                $item->thumbnail_url = $getImageUrl($item->thumbnail->path ?? null);
            });

        $recentVehicles = Vehicle::with(['brand', 'pictures' => function($query) {
            $query->where('thumbnail', 1);
        }])->latest()->limit(8)->get()
            ->each(function($item) use ($getImageUrl) {
                $item->brand_logo_url = $getImageUrl($item->brand->logo->path ?? null);
                $item->thumbnail_url = $getImageUrl(optional($item->pictures->first())->path);
            });

        $popularVehicles = Vehicle::with(['brand', 'pictures' => function($query) {
            $query->where('thumbnail', 1);
        }])
        ->whereHas('views', fn (Builder $query) => $query->where('created_at', '>', now()->subMonths(3)))
        ->withCount('views')
        ->orderBy('views_count', 'desc')
        ->limit(10)
        ->get()
        ->each(function($item) use ($getImageUrl) {
            $item->brand_logo_url = $getImageUrl($item->brand->logo->path ?? null);
            $item->thumbnail_url = $getImageUrl(optional($item->pictures->first())->path);
        });

        $logo = Option::where('type', 'logo')->with('thumbnail')->first();
        $logo_url = $getImageUrl($logo->thumbnail->path ?? null);

        return response()->json([
            // 'bikeBrands' => $bikeBrands,
            // 'carBrands' => $carBrands,
            'posts' => $posts,
            'stickies' => $stickies,
            // 'recentVehicles' => $recentVehicles,
            'popularVehicles' => $popularVehicles,
            'logo' => $logo_url
        ]);
    }


    public function search(Request $request) {
        $getImageUrl = function ($image) {
            return $image ? asset('storage/' . $image->path) : null;
        };

        $searchVehicleIds = Vehicle::search($request->q)->get()->pluck('id');
        $vehicles = Spec::find(1)
                        ->vehicles()
                        ->wherePivotIn('vehicle_id', $searchVehicleIds)
                        ->orderByPivot('value', 'desc')
                        ->get();

        $vehicles->each(function($vehicle) use ($getImageUrl) {
            $firstPicture = $vehicle->pictures->first();
            $vehicle->thumbnail_url = $firstPicture ? $getImageUrl($firstPicture) : null;
            // Hide the pictures attribute
            $vehicle->makeHidden('pictures');
        });

        return response()->json([
            'vehicles' => $vehicles,

        ]);
    }

    public function getNewData(Request $request)
    {
        $getImageUrl = function ($path) {
            return $path ? asset('storage/' . $path) : null;
        };

        $timestamp = $request->query('timestamp');
        $timestamp = $timestamp ? date('Y-m-d H:i:s', strtotime($timestamp)) : now()->subDay()->toDateTimeString();

        // Mengambil data berita terbaru
        $newBlog = Blog::with('thumbnail')
                       ->where('published', true)
                       ->where('created_at', '>', $timestamp)
                       ->orderBy('created_at', 'desc')
                       ->first();

        if ($newBlog) {
            $newBlog->thumbnail_url = $getImageUrl($newBlog->thumbnail->path ?? null);
        }

        // Mengambil data kendaraan terbaru
        $newVehicle = Vehicle::with(['brand', 'pictures' => function($query) {
            $query->where('thumbnail', 1);
        }])
        ->where('created_at', '>', $timestamp)
        ->latest()
        ->first();

        if ($newVehicle) {
            $newVehicle->brand_logo_url = $getImageUrl($newVehicle->brand->logo->path ?? null);
            $newVehicle->thumbnail_url = $getImageUrl(optional($newVehicle->pictures->first())->path);
        }

        return response()->json([
            'newBlog' => $newBlog,
            'newVehicle' => $newVehicle
        ]);
    }



}

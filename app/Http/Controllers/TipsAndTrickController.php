<?php

namespace App\Http\Controllers;

use App\Models\TipsAndTrick;
use App\Models\Brand;
use App\Models\Blog;
use App\Models\Option;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TipsAndTrickController extends Controller
{
    public function index(Request $request)
    {
        $tipsAndTricks = TipsAndTrick::whereHas('blog', fn($q) =>
            $q->where('published', true)
        )->with(['blog.thumbnail'])->latest()->paginate(10);

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
            $remainderArticles = Blog::with('thumbnail')
                ->latest()->where('published', true)
                ->limit($newsLimit)->get();

            $stickies = $stickies->concat($featured)->concat($remainderArticles);
        }

        $banner = Option::where([
            ['type', 'banner'],
            ['name', 'tips']
        ])->with('thumbnail')->first();

        return view('tips.index', [
            'tipsAndTricks' => $tipsAndTricks,
            'bikeBrands' => Brand::limit(14)
                ->whereHas('vehicles.type', fn (Builder $query) =>
                    $query->where('name', 'sepeda motor'))
                ->withCount('vehicles')->having('vehicles_count', '>', 0)
                ->orderBy('vehicles_count', 'desc')->get(),

            'carBrands' => Brand::limit(14)
                ->whereHas('vehicles.type', fn (Builder $query) =>
                    $query->where('name', 'mobil'))
                ->withCount('vehicles')->having('vehicles_count', '>', 0)
                ->orderBy('vehicles_count', 'desc')->get(),

            'banner' => is_null($banner) || is_null($banner->thumbnail) ? null : $banner->thumbnail,
            'stickies' => $stickies,
            'recentVehicles' => Vehicle::with('brand')->latest()->limit(8)->get(),
            'popularVehicles' => Vehicle::with('brand')
                ->whereHas('views', fn (Builder $query) =>
                    $query->where('created_at', '>', now()->subMonths(3)))
                ->withCount('views')->orderBy('views_count', 'desc')->limit(10)->get(),
            'logo' => Option::where('type', 'logo')->with('thumbnail')->first()
        ]);
    }

    // public function show($id)
    // {
    //     $tipsAndTrick = TipsAndTrick::findOrFail($id);

    //     $stickies = TipsAndTrick::where('published', true)
    //         ->where('is_sticky', true)->latest()->get();

    //     $featured = TipsAndTrick::where('published', true)
    //         ->where('featured', true)->latest()->limit(3)->get();

    //     $newsLimit = 3 - $featured->count();
    //     if ($newsLimit > 0 && $newsLimit <= 3) {
    //         $remainder = TipsAndTrick::where('published', true)
    //             ->latest()->limit($newsLimit)->get();

    //         $stickies = $stickies->concat($featured)->concat($remainder);
    //     }

    //     return view('tips.show', [
    //         'bikeBrands' => Brand::limit(14)
    //             ->whereHas('vehicles.type', fn (Builder $q) =>
    //                 $q->where('name', 'sepeda motor'))
    //             ->withCount('vehicles')->having('vehicles_count', '>', 0)
    //             ->orderBy('vehicles_count', 'desc')->get(),

    //         'carBrands' => Brand::limit(14)
    //             ->whereHas('vehicles.type', fn (Builder $q) =>
    //                 $q->where('name', 'mobil'))
    //             ->withCount('vehicles')->having('vehicles_count', '>', 0)
    //             ->orderBy('vehicles_count', 'desc')->get(),

    //         'tipsAndTrick' => $tipsAndTrick,
    //         'stickies' => $stickies,
    //         'recentVehicles' => Vehicle::with('brand')->latest()->limit(8)->get(),
    //         'popularVehicles' => Vehicle::with('brand')
    //             ->whereHas('views', fn (Builder $q) =>
    //                 $q->where('created_at', '>', now()->subMonths(3)))
    //             ->withCount('views')->orderBy('views_count', 'desc')->limit(10)->get(),
    //         'logo' => Option::where('type', 'logo')->with('thumbnail')->first()
    //     ]);
    // }
    
}

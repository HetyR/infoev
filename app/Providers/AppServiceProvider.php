<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Option;
use App\Models\Brand;
use App\Models\Vehicle;
use App\Models\Blog;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // View composer untuk semua view
        View::composer('*', function ($view) {
            // Ambil logo
            $logo = Option::where('type', 'logo')->with('thumbnail')->first();

            // Ambil 14 merk motor
            $bikeBrands = Brand::whereHas('vehicles.type', function ($q) {
                    $q->where('name', 'sepeda motor');
                })
                ->withCount('vehicles')
                ->having('vehicles_count', '>', 0)
                ->orderByDesc('vehicles_count')
                ->limit(14)
                ->get();

            // Ambil 14 merk mobil
            $carBrands = Brand::whereHas('vehicles.type', function ($q) {
                    $q->where('name', 'mobil');
                })
                ->withCount('vehicles')
                ->having('vehicles_count', '>', 0)
                ->orderByDesc('vehicles_count')
                ->limit(14)
                ->get();

            // Ambil kendaraan terbaru
            $recentVehicles = Vehicle::with('brand')
                ->latest()
                ->limit(8)
                ->get();

            // Ambil kendaraan terpopuler (dalam 3 bulan terakhir)
            $popularVehicles = Vehicle::with('brand')
                ->whereHas('views', function ($q) {
                    $q->where('created_at', '>', now()->subMonths(3));
                })
                ->withCount('views')
                ->orderByDesc('views_count')
                ->limit(10)
                ->get();

            // Ambil artikel sticky
            $stickies = Blog::with('thumbnail')
                ->select('sticky_articles.*', 'blogs.*')
                ->join('sticky_articles', 'blogs.id', '=', 'sticky_articles.blog_id')
                ->where('blogs.published', true)
                ->orderBy('sticky_articles.created_at', 'desc')
                ->get();

            $view->with([
                'logo' => $logo,
                'bikeBrands' => $bikeBrands,
                'carBrands' => $carBrands,
                'recentVehicles' => $recentVehicles,
                'popularVehicles' => $popularVehicles,
                'stickies' => $stickies,
            ]);
        });
    }
}

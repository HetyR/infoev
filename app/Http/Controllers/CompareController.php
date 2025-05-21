<?php

namespace App\Http\Controllers;

use App\Models\Compare;
use App\Models\Brand;
use App\Models\Option;
use App\Models\Vehicle;
use App\Models\Blog;
use App\Models\SpecCategory;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    public function index(Request $request)
    {
        $logo = Option::where('type', 'logo')->with('thumbnail')->first();

        $bikeBrands = Brand::limit(14)
            ->whereHas('vehicles.type', fn($query) => $query->where('name', 'sepeda motor'))
            ->withCount('vehicles')
            ->having('vehicles_count', '>', 0)
            ->orderBy('vehicles_count', 'desc')
            ->get();

        $carBrands = Brand::limit(14)
            ->whereHas('vehicles.type', fn($query) => $query->where('name', 'mobil'))
            ->withCount('vehicles')
            ->having('vehicles_count', '>', 0)
            ->orderBy('vehicles_count', 'desc')
            ->get();

        $banner = Option::where('type', 'banner')->with('thumbnail')->first();

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

        $vehicles = Vehicle::with('brand')->get();
        $brands = Brand::all();

        $compareList = session()->get('compare_list', []);
        $comparedVehicles = Vehicle::whereIn('id', $compareList)->with('brand')->get();

        $combinedList = $vehicles->map(fn($vehicle) => [
            'name' => $vehicle->brand->name . ' ' . $vehicle->name,
            'type' => 'vehicle'
        ])->concat($brands->map(fn($brand) => [
            'name' => $brand->name,
            'type' => 'brand'
        ]));

        $prefillVehicle1 = session()->pull('prefill_vehicle1');

        return view('compare.index', [
            'logo' => $logo,
            'bikeBrands' => $bikeBrands,
            'carBrands' => $carBrands,
            'posts' => Blog::with('thumbnail')
                ->latest()
                ->where('published', true)
                ->search($request->q)
                ->paginate(15),
            'banner' => $banner?->thumbnail,
            'recentVehicles' => Vehicle::with('brand')->latest()->limit(8)->get(),
            'popularVehicles' => Vehicle::with('brand')
                ->whereHas('views', fn($query) => $query->where('created_at', '>', now()->subMonths(3)))
                ->withCount('views')
                ->orderBy('views_count', 'desc')
                ->limit(10)
                ->get(),
            'stickies' => $stickies,
            'vehicles' => $vehicles,
            'vehicle1' => null,
            'vehicle2' => null,
            'specCategories' => collect(),
            'brands' => $brands,
            'combinedList' => $combinedList,
            'errorMessage' => null,
            'comparedVehicles' => $comparedVehicles,
            'prefillVehicle1' => $prefillVehicle1, // ✅ kirim ke view
        ]);
    }
    public function fetchComparison(Request $request)
    {
        $vehicle1 = null;
        $vehicle2 = null;
        $specCategories = [];

        $vehicle1Input = explode(' ', $request->vehicle1 ?? '', 2);
        $vehicle2Input = explode(' ', $request->vehicle2 ?? '', 2);

        if (count($vehicle1Input) >= 2) {
            [$brand1Name, $vehicle1Name] = $vehicle1Input;
            $brand1 = Brand::where('name', $brand1Name)->first();
            if ($brand1) {
                $vehicle1 = Vehicle::with('brand', 'pictures')->where('name', $vehicle1Name)->where('brand_id', $brand1->id)->first();
            }
        }

        if (count($vehicle2Input) >= 2) {
            [$brand2Name, $vehicle2Name] = $vehicle2Input;
            $brand2 = Brand::where('name', $brand2Name)->first();
            if ($brand2) {
                $vehicle2 = Vehicle::with('brand', 'pictures')->where('name', $vehicle2Name)->where('brand_id', $brand2->id)->first();
            }
        }

        $vehicleIds = array_filter([$vehicle1?->id, $vehicle2?->id]);

        if (!empty($vehicleIds)) {
            $specCategories = SpecCategory::with(['specs.vehicles' => function ($query) use ($vehicleIds) {
                $query->whereIn('vehicles.id', $vehicleIds);
            }])->get();
        }

        return response()->json([
            'vehicle1' => $vehicle1,
            'vehicle2' => $vehicle2,
            'specCategories' => $specCategories,
        ]);
    }

    public function showVehicle($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        return view('vehicle.show', compact('vehicle'));
    }

    public function addToCompare(Request $request)
    {
        $vehicleId = $request->input('vehicle_id');
        $compareList = $request->session()->get('compare_list', []);

        if (!in_array($vehicleId, $compareList)) {
            $compareList[] = $vehicleId;
            $request->session()->put('compare_list', $compareList);
        }

        $vehicle = Vehicle::with('brand')->find($vehicleId);
        if ($vehicle) {
            $request->session()->put('prefill_vehicle1', $vehicle->brand->name . ' ' . $vehicle->name);
        }

        return redirect()->route('compare.index');
    }
}

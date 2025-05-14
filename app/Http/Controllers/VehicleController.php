<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Option;
use App\Models\Spec;
use App\Models\SpecCategory;
use App\Models\Vehicle;
use App\Models\VehicleView;
use App\Models\SpecVehicle;
use Illuminate\Support\Facades\Log; // Impor kelas Log

use Illuminate\Http\Request;
use Auth;
use App\Models\LovedVehicle;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;

class VehicleController extends Controller
{

    public function show(Vehicle $vehicle) {

        if ($vehicle->name == 'all') abort(404);

        $specCategories = SpecCategory::with(['specs',
                        'specs.vehicles' => function ($query) use ($vehicle) {
                            $query->where('vehicles.id', $vehicle->id);
                        }])
                        ->whereRelation('specs.vehicles', 'vehicles.id', $vehicle->id)
                        ->orderBy('priority')
                        ->get();

        $highlightSpecIds = Spec::where('name', 'kapasitas')
                                ->orWhere('name', 'pengisian daya ac')
                                ->orWhere('name', 'kecepatan maksimal')
                                ->orWhere('name', 'jarak tempuh')
                                ->get()
                                ->pluck('id');
        $specs = Vehicle::find($vehicle->id)->specs()->wherePivotIn('spec_id', $highlightSpecIds)->get();
        $highlightSpecs = [];

        foreach ($specs as $spec) {
            $push = [];
            switch ($spec->name) {
                case 'Kapasitas':
                    $push['type'] = 'capacity';
                    $push['value'] = (float) $spec->pivot->value;
                    $push['unit'] = $spec->unit;
                    break;
                case 'Pengisian Daya AC':
                    $push['type'] = 'charge';
                    $push['value'] = (float) $spec->pivot->value;
                    $push['unit'] = $spec->unit;
                    $push['desc'] = $spec->pivot->value_desc;
                    break;
                case 'Kecepatan Maksimal':
                    $push['type'] = 'maxSpeed';
                    $push['value'] = (float) $spec->pivot->value;
                    $push['unit'] = $spec->unit;
                    break;
                case 'Jarak Tempuh':
                    $push['type'] = 'range';
                    $push['value'] = (float) $spec->pivot->value;
                    $push['unit'] = $spec->unit;
                    break;
                default:
                    break;
            }

            array_push($highlightSpecs, $push);
        }

        $stickies = Blog::with('thumbnail')
                        ->select('sticky_articles.*', 'blogs.*') // Perbaiki penulisan kolom
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

        if ($vehicle->countView()) {
            VehicleView::createViewLog($vehicle);
        }


        return view('vehicle.show', [
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
            'specCategories' => $specCategories,
            'highlightSpecs' => $highlightSpecs,
            'vehicle' => $vehicle,
            'pictures' => $vehicle->pictures,
            'comments' => $vehicle->comments()->where('parent_id', null)->latest()->get(),
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

    public function toggleLove($id)
{
    $user = auth()->user();
    $vehicle = Vehicle::findOrFail($id);

    $lovedVehicle = LovedVehicle::where('vehicle_id', $vehicle->id)
        ->where('user_id', $user->id)
        ->first();

    if ($lovedVehicle) {
        $lovedVehicle->delete();
    } else {
        LovedVehicle::create([
            'vehicle_id' => $vehicle->id,
            'user_id' => $user->id,
        ]);
    }

    return redirect()->back();
}

        public function showVehicleSpecs($id)
        {
            $vehicle = Vehicle::find($id);
            return view('components.vehicle.spec-highlight', ['vehicle' => $vehicle]);
        }

public function showKalkulasiForm()
{
    $vehicles = Vehicle::with('brand')->get();

    $logo = Option::where('type', 'logo')->with('thumbnail')->first();
    $bikeBrands = Brand::whereHas('vehicles.type', fn ($q) => $q->where('name', 'sepeda motor'))->withCount('vehicles')->having('vehicles_count', '>', 0)->orderByDesc('vehicles_count')->limit(14)->get();
    $carBrands = Brand::whereHas('vehicles.type', fn ($q) => $q->where('name', 'mobil'))->withCount('vehicles')->having('vehicles_count', '>', 0)->orderByDesc('vehicles_count')->limit(14)->get();
    $banner = Option::where('type', 'banner')->with('thumbnail')->first();
    $recentVehicles = Vehicle::with('brand')->latest()->limit(8)->get();
    $popularVehicles = Vehicle::with('brand')->withCount('views')->orderByDesc('views_count')->limit(10)->get();

    $featured = Blog::with('thumbnail')->latest()->where('published', true)->where('featured', true)->limit(3)->get();
    $stickies = Blog::with('thumbnail')->join('sticky_articles', 'blogs.id', '=', 'sticky_articles.blog_id')->where('blogs.published', true)->orderBy('sticky_articles.created_at', 'desc')->get();

    $newsLimit = 3 - $featured->count();
    if ($newsLimit > 0 && $newsLimit <= 3) {
        $extra = Blog::with('thumbnail')->latest()->where('published', true)->limit($newsLimit)->get();
        $stickies = $stickies->concat($featured)->concat($extra);
    }

    return view('vehicle.kalkulasi.index', [
        'vehicles' => $vehicles,
        'logo' => $logo,
        'bikeBrands' => $bikeBrands,
        'carBrands' => $carBrands,
        'recentVehicles' => $recentVehicles,
        'popularVehicles' => $popularVehicles,
        'stickies' => $stickies,
        'banner' => $banner?->thumbnail,
    ]);
}
    public function processKalkulasi(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'harga_listrik' => 'required|numeric|min:0',
            'jarak' => 'nullable|numeric|min:1',
        ]);

        $vehicle = Vehicle::findOrFail($request->vehicle_id);
        $specs = $vehicle->specs()->get()->keyBy('name');

        $kapasitas = (float) optional($specs->get('Kapasitas'))->pivot->value;
        $jarakTempuh = (float) optional($specs->get('Jarak Tempuh'))->pivot->value;

        if ($kapasitas == 0 || $jarakTempuh == 0) {
            return back()->with('error', 'Data spesifikasi kendaraan tidak lengkap (Kapasitas atau Jarak Tempuh).');
        }

        $hargaPerKwh = $request->harga_listrik;
        $kwhPerKm = $kapasitas / $jarakTempuh;
        $biayaPerKm = $kwhPerKm * $hargaPerKwh;
        $biayaPer100Km = $biayaPerKm * 100;

        $jarakBulanan = $request->jarak;
        $biayaBulanan = $jarakBulanan ? $biayaPerKm * $jarakBulanan : null;

        return view('vehicle.kalkulasi.index', [
            'vehicle' => $vehicle,
            'result' => [
                'kwh_per_km' => round($kwhPerKm, 3),
                'biaya_per_km' => round($biayaPerKm),
                'biaya_per_100_km' => round($biayaPer100Km),
                'biaya_bulanan' => $biayaBulanan ? round($biayaBulanan) : null,
                'harga_kwh' => $hargaPerKwh,
                'jarak_bulanan' => $jarakBulanan,
            ],
        ]);
    }
}





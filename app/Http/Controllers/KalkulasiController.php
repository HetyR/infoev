<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Blog;
use App\Models\Option;
use App\Models\Type;
use App\Models\Vehicle;
use App\Models\Spec;
use App\Models\SpecVehicle;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class KalkulasiController extends Controller
{
    /**
     * Display the EV calculator page
     */
public function index()
{
    $types = Type::select('id', 'name', 'slug')->get();
    $vehicles = Vehicle::with('brand')->get();

    $logo = Option::where('type', 'logo')->with('thumbnail')->first();
    $bikeBrands = Brand::whereHas('vehicles.type', fn ($q) => $q->where('name', 'sepeda motor'))
        ->withCount('vehicles')->having('vehicles_count', '>', 0)
        ->orderByDesc('vehicles_count')->limit(14)->get();

    $carBrands = Brand::whereHas('vehicles.type', fn ($q) => $q->where('name', 'mobil'))
        ->withCount('vehicles')->having('vehicles_count', '>', 0)
        ->orderByDesc('vehicles_count')->limit(14)->get();

    $banner = Option::where('type', 'banner')->with('thumbnail')->first();
    $recentVehicles = Vehicle::with('brand')->latest()->limit(8)->get();
    $popularVehicles = Vehicle::with('brand')->withCount('views')->orderByDesc('views_count')->limit(10)->get();

    $featured = Blog::with('thumbnail')->latest()->where('published', true)->where('featured', true)->limit(3)->get();
    $stickies = Blog::with('thumbnail')->join('sticky_articles', 'blogs.id', '=', 'sticky_articles.blog_id')
        ->where('blogs.published', true)
        ->orderBy('sticky_articles.created_at', 'desc')->get();

    $newsLimit = 3 - $featured->count();
    if ($newsLimit > 0 && $newsLimit <= 3) {
        $extra = Blog::with('thumbnail')->latest()->where('published', true)->limit($newsLimit)->get();
        $stickies = $stickies->concat($featured)->concat($extra);
    }

    return view('vehicle.kalkulasi.index', [
        'types' => $types,
        'vehicles' => $vehicles,
        'logo' => $logo?->thumbnail,
        'banner' => $banner?->thumbnail,
        'bikeBrands' => $bikeBrands,
        'carBrands' => $carBrands,
        'recentVehicles' => $recentVehicles,
        'popularVehicles' => $popularVehicles,
        'stickies' => $stickies,
    ]);
}


    /**
     * Get brands based on vehicle type
     */
    public function getBrandsByType(Request $request)
    {
        $typeId = $request->type_id;
        
        $brands = Brand::whereHas('vehicles', function ($query) use ($typeId) {
            $query->where('type_id', $typeId);
        })->select('id', 'name', 'slug')->get();

        return response()->json(['brands' => $brands]);
    }

    /**
     * Get vehicles based on brand
     */
    public function getVehiclesByBrand(Request $request)
    {
        $brandId = $request->brand_id;
        
        $vehicles = Vehicle::where('brand_id', $brandId)
            ->select('id', 'name', 'slug')
            ->get();

        return response()->json(['vehicles' => $vehicles]);
    }

    /**
     * Get vehicle specifications for EV calculation
     */
    public function getVehicleSpecs(Request $request)
    {
        $vehicleId = $request->vehicle_id;
        
        try {
            $vehicle = Vehicle::with(['brand', 'specs'])->findOrFail($vehicleId);
            
            // Mencari spec yang terkait dengan perhitungan EV
            $batteryCapacity = $this->findSpecValue($vehicle, ['kapasitas', 'Kapasitas', 'Battery Capacity']);
            $energyConsumption = $this->findSpecValue($vehicle, ['konsumsi energi', 'Konsumsi Energi', 'Energy Consumption']);
            $maxRange = $this->findSpecValue($vehicle, ['jarak tempuh', 'Jarak Tempuh', 'Max Range']);
            $chargingPower = $this->findSpecValue($vehicle, ['pengisian daya', 'Pengisian Daya', 'Charging Power']);

            // Extract the relevant specs into a more user-friendly format
            $evSpecs = [
                'id' => $vehicle->id,
                'name' => $vehicle->name,
                'brand' => $vehicle->brand->name,
                'battery_capacity' => $batteryCapacity ? floatval($batteryCapacity) : null, // kWh
                'energy_consumption' => $energyConsumption ? floatval($energyConsumption) : null, // kWh/100km
                'max_range' => $maxRange ? floatval($maxRange) : null, // km
                'charging_power' => $chargingPower ? floatval($chargingPower) : null // kW
            ];

            return response()->json(['success' => true, 'vehicle_specs' => $evSpecs]);
        } catch (\Exception $e) {
            Log::error('Error getting vehicle specs: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mendapatkan spesifikasi kendaraan']);
        }
    }

    /**
     * Helper function to find spec value by various possible names
     */
    private function findSpecValue($vehicle, $possibleNames)
    {
        foreach ($vehicle->specs as $spec) {
            if (in_array(strtolower($spec->name), array_map('strtolower', $possibleNames))) {
                return $spec->pivot->value;
            }
        }
        return null;
    }

    /**
     * Calculate EV costs based on specs and user inputs
     */
    public function calculateCosts(Request $request)
    {
        // Validate request
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'daily_distance' => 'required|numeric|min:0',
            'electricity_price' => 'required|numeric|min:0'
        ]);

        try {
            // Get vehicle data
            $vehicle = Vehicle::with(['specs'])->findOrFail($request->vehicle_id);
            
            // Extract specs
            $batteryCapacity = $this->findSpecValue($vehicle, ['kapasitas', 'Kapasitas', 'Battery Capacity']);
            $energyConsumption = $this->findSpecValue($vehicle, ['konsumsi energi', 'Konsumsi Energi', 'Energy Consumption']);
            $maxRange = $this->findSpecValue($vehicle, ['jarak tempuh', 'Jarak Tempuh', 'Max Range']);

            // Konversi nilai ke float
            $batteryCapacity = $batteryCapacity ? floatval($batteryCapacity) : null;
            $energyConsumption = $energyConsumption ? floatval($energyConsumption) : null;
            $maxRange = $maxRange ? floatval($maxRange) : null;

            // If specs are not available, use calculated values
            if (!$energyConsumption && $batteryCapacity && $maxRange) {
                $energyConsumption = ($batteryCapacity / $maxRange) * 100;
            }

            // If still don't have required specs, return error
            if (!$batteryCapacity || !$energyConsumption) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data spesifikasi kendaraan tidak lengkap untuk kalkulasi'
                ]);
            }

            // Get user inputs
            $dailyDistance = floatval($request->daily_distance);
            $electricityPrice = floatval($request->electricity_price); // Rp/kWh

            // Calculate costs
            $costPerKm = ($energyConsumption / 100) * $electricityPrice;
            $costPer100Km = $energyConsumption * $electricityPrice;
            $fullChargeCost = $batteryCapacity * $electricityPrice;
            $dailyCost = $costPerKm * $dailyDistance;
            $monthlyCost = $dailyCost * 30;
            $rangePerCharge = $maxRange ?: ($batteryCapacity / ($energyConsumption / 100));

            // Return calculated results
            return response()->json([
                'success' => true,
                'results' => [
                    'cost_per_km' => round($costPerKm, 2),
                    'cost_per_100km' => round($costPer100Km, 2),
                    'full_charge_cost' => round($fullChargeCost, 2),
                    'daily_cost' => round($dailyCost, 2),
                    'monthly_cost' => round($monthlyCost, 2),
                    'range_per_charge' => round($rangePerCharge, 2)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error calculating EV costs: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Terjadi kesalahan saat kalkulasi'
            ]);
        }
    }
}
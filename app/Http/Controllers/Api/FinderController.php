<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Option;
use App\Models\Brand;
use App\Models\Blog;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class FinderController extends Controller
{
    //
    public function index(Request $request)
    {
        $featured = Blog::with('thumbnail')
            ->latest()
            ->where('published', true)
            ->where('featured', true)
            ->limit(3)
            ->get();

        $newsLimit = 3 - $featured->count();
        if ($newsLimit > 0 && $newsLimit <= 3) {
            // Fetch additional articles if needed (not shown in this snippet)
        }

        // Query to get unique years from spec_vehicle where spec_id is 1
        $years = DB::table('spec_vehicle')
            ->where('spec_id', 1)
            ->distinct()
            ->pluck('value');

        // Mengambil sistem penggerak
        $driveSystems = DB::table('spec_lists')
            ->where('spec_id', 46)
            ->get(['list as name']);

        // Mengambil teknologi baterai
        $teknologibaterai = DB::table('spec_lists')
            ->where('spec_id', 10)
            ->get(['list as name']);

        // Mengambil type dari tabel types
        $types = DB::table('types')
            ->get(['name']);

        return response()->json([
            'years' => $years,
            'driveSystems' => $driveSystems,
            'teknologibaterai' => $teknologibaterai,
            'types' => $types,
        ]);
    }


    public function show(Request $request)
    {
        $getImageUrl = function ($image) {
            return $image ? asset('storage/' . $image->path) : null;
        };

        $vehicles = Vehicle::query()
            ->when($request->input('brands'), function ($query, $brands) {
                return $query->whereIn('brand_id', $brands);
            })
            ->when($request->input('years'), function ($query, $years) {
                $yearValues = DB::table('spec_vehicle')
                    ->where('spec_id', 1)
                    ->whereIn('value', $years)
                    ->pluck('vehicle_id');
                return $query->whereIn('id', $yearValues);
            })
            ->when($request->input('driveSystems'), function ($query, $driveSystems) {
                $vehicleIds = DB::table('spec_vehicle as sv')
                    ->join('spec_list_spec_vehicle as slsv', 'sv.id', '=', 'slsv.spec_vehicle_id')
                    ->join('spec_lists as sl', 'sl.id', '=', 'slsv.spec_list_id')
                    ->where('sv.spec_id', 46)
                    ->whereIn('sl.list', $driveSystems)
                    ->pluck('sv.vehicle_id');
                return $query->whereIn('id', $vehicleIds);
            })
            ->when($request->input('teknologibaterai'), function ($query, $teknologibaterai) {
                $vehicleIds = DB::table('spec_vehicle as sv')
                    ->join('spec_list_spec_vehicle as slsv', 'sv.id', '=', 'slsv.spec_vehicle_id')
                    ->join('spec_lists as sl', 'sl.id', '=', 'slsv.spec_list_id')
                    ->where('sv.spec_id', 10)
                    ->whereIn('sl.list', $teknologibaterai)
                    ->pluck('sv.vehicle_id');
                return $query->whereIn('id', $vehicleIds);
            })
            ->when($request->input('price'), function ($query, $price) {
                $vehicleIds = DB::table('spec_vehicle')
                    ->where('spec_id', 2)
                    ->where('value', '<=', $price)
                    ->pluck('vehicle_id');
                return $query->whereIn('id', $vehicleIds);
            })
            ->when($request->input('maxspeed'), function ($query, $maxspeed) {
                $vehicleIds = DB::table('spec_vehicle')
                    ->where('spec_id', 6)
                    ->where('value', '<=', $maxspeed)
                    ->pluck('vehicle_id');
                return $query->whereIn('id', $vehicleIds);
            })
            ->when($request->input('batterykapasitas'), function ($query, $batterykapasitas) {
                $vehicleIds = DB::table('spec_vehicle')
                    ->where('spec_id', 8)
                    ->where('value', '<=', $batterykapasitas)
                    ->pluck('vehicle_id');
                return $query->whereIn('id', $vehicleIds);
            })
            ->when($request->input('chargingtime'), function ($query, $chargingtime) {
                $vehicleIds = DB::table('spec_vehicle')
                    ->where('spec_id', 14)
                    ->where('value', '<=', $chargingtime)
                    ->pluck('vehicle_id');
                return $query->whereIn('id', $vehicleIds);
            })
            ->when($request->has('features') && in_array('alarm', $request->input('features')), function ($query) {
                $vehicleIds = DB::table('spec_vehicle')
                    ->where('spec_id', 50)
                    ->where('value_bool', 1)
                    ->pluck('vehicle_id');
                return $query->whereIn('id', $vehicleIds);
            })
            ->when($request->has('features') && in_array('lampudepan', $request->input('features')), function ($query) {
                $vehicleIds = DB::table('spec_vehicle')
                    ->where('spec_id', 43)
                    ->where('value_bool', 1)
                    ->pluck('vehicle_id');
                return $query->whereIn('id', $vehicleIds);
            })
            ->when($request->has('features') && in_array('lampubelakang', $request->input('features')), function ($query) {
                $vehicleIds = DB::table('spec_vehicle')
                    ->where('spec_id', 44)
                    ->where('value_bool', 1)
                    ->pluck('vehicle_id');
                return $query->whereIn('id', $vehicleIds);
            })
            ->when($request->has('features') && in_array('lampuhazard', $request->input('features')), function ($query) {
                $vehicleIds = DB::table('spec_vehicle')
                    ->where('spec_id', 45)
                    ->where('value_bool', 1)
                    ->pluck('vehicle_id');
                return $query->whereIn('id', $vehicleIds);
            })
            ->when($request->input('types'), function ($query, $brands) {
                return $query->whereIn('type_id', $brands);
            })

            ->when($request->input('jarak'), function ($query, $jarak) {
                $vehicleIds = DB::table('spec_vehicle')
                    ->where('spec_id', 11)
                    ->where('value', '<=', $jarak)
                    ->pluck('vehicle_id');
                return $query->whereIn('id', $vehicleIds);
            })
            ->with(['brand', 'specs'])
            ->get();

        $vehicles->transform(function($vehicle) use ($getImageUrl) {
            $firstPicture = $vehicle->pictures->first();
            $vehicle->thumbnail_url = $firstPicture ? $getImageUrl($firstPicture) : null;

            $specs = $vehicle->specs->map(function($spec) {
                return [
                    'spec_id' => $spec->id,
                    'name' => $spec->name,
                    'value' => $spec->pivot->value,
                ];
            });

            return [
                'id' => $vehicle->id,
                'name' => $vehicle->name,
                'slug' => $vehicle->slug,
                'thumbnail_url' => $vehicle->thumbnail_url,
                'brand' => $vehicle->brand->name, // Nama merek
                'specs' => $specs->where('spec_id', 1)->values()->all(), // Filter hanya spec_id 1
            ];
        });

        return response()->json([
            'vehicles' => $vehicles,
        ]);
    }

    public function search(Request $request)
    {
        $getImageUrl = function ($image) {
            return $image ? asset('storage/' . $image->path) : null;
        };

        $vehicles = Vehicle::query()
            ->when($request->input('brands'), function ($query, $brands) {
                return $query->whereIn('brand_id', $brands);
            })
            ->when($request->input('years'), function ($query, $years) {
                $yearValues = DB::table('spec_vehicle')
                    ->where('spec_id', 1)
                    ->whereIn('value', $years)
                    ->pluck('vehicle_id');
                return $query->whereIn('id', $yearValues);
            })
            ->when($request->input('driveSystems'), function ($query, $driveSystems) {
                $vehicleIds = DB::table('spec_vehicle as sv')
                    ->join('spec_list_spec_vehicle as slsv', 'sv.id', '=', 'slsv.spec_vehicle_id')
                    ->join('spec_lists as sl', 'sl.id', '=', 'slsv.spec_list_id')
                    ->where('sv.spec_id', 46)
                    ->whereIn('sl.list', $driveSystems)
                    ->pluck('sv.vehicle_id');
                return $query->whereIn('id', $vehicleIds);
            })
            ->when($request->input('teknologibaterai'), function ($query, $teknologibaterai) {
                $vehicleIds = DB::table('spec_vehicle as sv')
                    ->join('spec_list_spec_vehicle as slsv', 'sv.id', '=', 'slsv.spec_vehicle_id')
                    ->join('spec_lists as sl', 'sl.id', '=', 'slsv.spec_list_id')
                    ->where('sv.spec_id', 10)
                    ->whereIn('sl.list', $teknologibaterai)
                    ->pluck('sv.vehicle_id');
                return $query->whereIn('id', $vehicleIds);
            })
            ->when($request->input('price_min') || $request->input('price_max'), function ($query) use ($request) {
                $minPrice = $request->input('price_min', 0);
                $maxPrice = $request->input('price_max', PHP_INT_MAX);
                $vehicleIds = DB::table('spec_vehicle')
                    ->where('spec_id', 2)
                    ->whereBetween('value', [$minPrice, $maxPrice])
                    ->pluck('vehicle_id');
                return $query->whereIn('id', $vehicleIds);
            })
            ->when($request->input('maxspeed_min') || $request->input('maxspeed_max'), function ($query) use ($request) {
                $minSpeed = $request->input('maxspeed_min', 0);
                $maxSpeed = $request->input('maxspeed_max', PHP_INT_MAX);
                $vehicleIds = DB::table('spec_vehicle')
                    ->where('spec_id', 6)
                    ->whereBetween('value', [$minSpeed, $maxSpeed])
                    ->pluck('vehicle_id');
                return $query->whereIn('id', $vehicleIds);
            })
            ->when($request->input('batterykapasitas_min') || $request->input('batterykapasitas_max'), function ($query) use ($request) {
                $minBattery = $request->input('batterykapasitas_min', 0);
                $maxBattery = $request->input('batterykapasitas_max', PHP_INT_MAX);
                $vehicleIds = DB::table('spec_vehicle')
                    ->where('spec_id', 8)
                    ->whereBetween('value', [$minBattery, $maxBattery])
                    ->pluck('vehicle_id');
                return $query->whereIn('id', $vehicleIds);
            })
            ->when($request->input('chargingtime_min') || $request->input('chargingtime_max'), function ($query) use ($request) {
                $minCharging = $request->input('chargingtime_min', 0);
                $maxCharging = $request->input('chargingtime_max', PHP_INT_MAX);
                $vehicleIds = DB::table('spec_vehicle')
                    ->where('spec_id', 14)
                    ->whereBetween('value', [$minCharging, $maxCharging])
                    ->pluck('vehicle_id');
                return $query->whereIn('id', $vehicleIds);
            })
            ->when($request->input('jarak_min') || $request->input('jarak_max'), function ($query) use ($request) {
                $minDistance = $request->input('jarak_min', 0);
                $maxDistance = $request->input('jarak_max', PHP_INT_MAX);
                $vehicleIds = DB::table('spec_vehicle')
                    ->where('spec_id', 11)
                    ->whereBetween('value', [$minDistance, $maxDistance])
                    ->pluck('vehicle_id');
                return $query->whereIn('id', $vehicleIds);
            })
            ->when($request->has('features') && in_array('alarm', $request->input('features')), function ($query) {
                $vehicleIds = DB::table('spec_vehicle')
                    ->where('spec_id', 50)
                    ->where('value_bool', 1)
                    ->pluck('vehicle_id');
                return $query->whereIn('id', $vehicleIds);
            })
            ->when($request->has('features') && in_array('lampudepan', $request->input('features')), function ($query) {
                $vehicleIds = DB::table('spec_vehicle')
                    ->where('spec_id', 43)
                    ->where('value_bool', 1)
                    ->pluck('vehicle_id');
                return $query->whereIn('id', $vehicleIds);
            })
            ->when($request->has('features') && in_array('lampubelakang', $request->input('features')), function ($query) {
                $vehicleIds = DB::table('spec_vehicle')
                    ->where('spec_id', 44)
                    ->where('value_bool', 1)
                    ->pluck('vehicle_id');
                return $query->whereIn('id', $vehicleIds);
            })
            ->when($request->has('features') && in_array('lampuhazard', $request->input('features')), function ($query) {
                $vehicleIds = DB::table('spec_vehicle')
                    ->where('spec_id', 45)
                    ->where('value_bool', 1)
                    ->pluck('vehicle_id');
                return $query->whereIn('id', $vehicleIds);
            })
            ->when($request->input('types'), function ($query, $types) {
                return $query->whereIn('type_id', $types);
            })
            ->with(['brand', 'specs'])
            ->get();

        $vehicles->transform(function($vehicle) use ($getImageUrl) {
            $firstPicture = $vehicle->pictures->first();
            $vehicle->thumbnail_url = $firstPicture ? $getImageUrl($firstPicture) : null;

            $specs = $vehicle->specs->map(function($spec) {
                return [
                    'spec_id' => $spec->id,
                    'name' => $spec->name,
                    'value' => $spec->pivot->value,
                ];
            });

            return [
                'id' => $vehicle->id,
                'name' => $vehicle->name,
                'slug' => $vehicle->slug,
                'thumbnail_url' => $vehicle->thumbnail_url,
                'brand' => $vehicle->brand->name, // Nama merek
                'specs' => $specs->where('spec_id', 1)->values()->all(), // Filter hanya spec_id 1
            ];
        });

        return response()->json([
            'vehicles' => $vehicles,
        ]);
    }



}
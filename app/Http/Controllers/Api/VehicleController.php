<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Option;
use App\Models\Spec;
use App\Models\SpecCategory;
use App\Models\Vehicle;
use App\Models\SpecVehicle;
use App\Models\VehicleView;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
class VehicleController extends Controller
{
    public function show(Vehicle $vehicle, Request $request) {
        if ($vehicle->name == 'all') {
            return response()->json(['error' => 'Resource not found'], 404);
        }
    
        $specCategories = SpecCategory::with(['specs',
            'specs.vehicles' => function ($query) use ($vehicle) {
                $query->where('vehicles.id', $vehicle->id);
            }])
            ->whereRelation('specs.vehicles', 'vehicles.id', $vehicle->id)
            ->orderBy('priority')
            ->get();
    
        $highlightSpecIds = Spec::whereIn('name', ['kapasitas', 'pengisian daya ac', 'kecepatan maksimal', 'jarak tempuh'])
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
    
        // Prepare affiliate links data
        $affiliateLinks = $vehicle->affiliateLinks->map(function($affiliate) {
            return [
                'link' => $affiliate->link,
                'marketplace_logo' => asset('storage/' . $affiliate->marketplace->logo->path),
            ];
        });
    
        return response()->json([
            'specCategories' => $specCategories,
            'highlightSpecs' => $highlightSpecs,
            'vehicle' => $vehicle,
            'affiliateLinks' => $affiliateLinks, // Add affiliate links to JSON response
            // Add other data here...
        ]);
    }
    
}    
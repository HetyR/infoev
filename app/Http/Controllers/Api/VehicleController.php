<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;
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
use App\Models\Type;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    public function show(Vehicle $vehicle, Request $request)
    {
        if ($vehicle->name == 'all') {
            return response()->json(['error' => 'Resource not found'], 404);
        }

        $specCategories = SpecCategory::with([
            'specs',
            'specs.vehicles' => function ($query) use ($vehicle) {
                $query->where('vehicles.id', $vehicle->id);
            },
        ])
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
        $affiliateLinks = $vehicle->affiliateLinks->map(function ($affiliate) {
            return [
                'link' => $affiliate->link,
                'marketplace_logo' => asset('storage/' . $affiliate->marketplace->logo->path),
            ];
        });
 
        // Cek apakah user login atau guest
        $user = Auth::guard('sanctum')->user();
 
        $isFavorite = false;
        if ($user) {
            $isFavorite = $user->lovedVehicles()->where('vehicle_id', $vehicle->id)->exists(); 
        }

        return response()->json([
            'specCategories' => $specCategories,
            'highlightSpecs' => $highlightSpecs,
            'vehicle' => $vehicle,
            'affiliateLinks' => $affiliateLinks, // Add affiliate links to JSON response
            'isLoved' => $isFavorite, // Tambahkan flag favorit
            // Add other data here...
        ]);
    }

    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'type' => 'required|exists:types,id',
    //         'brand' => 'required|exists:brands,id',
    //         'spec_ids' => 'array',
    //         'value_types' => 'array',
    //         'values' => 'array',
    //         'value_descriptions' => 'array',
    //         'pictures.*' => 'file|image|max:2048',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     $type = Type::find($request->type);
    //     $brand = Brand::find($request->brand);

    //     $vehicle = new Vehicle;
    //     $vehicle->name = $request->name;
    //     $vehicle->type()->associate($type);
    //     $vehicle->brand()->associate($brand);
    //     $vehicle->save();

    //     // Process specs
    //     $specIds = $request->spec_ids ?? [];
    //     $specTypes = $request->value_types ?? [];
    //     $specValues = $request->values ?? [];
    //     $specDescriptions = $request->value_descriptions ?? [];

    //     for ($i = 0; $i < count($specValues); $i++) {
    //         $specId = $specIds[$i] ?? null;
    //         $specType = $specTypes[$i] ?? null;
    //         $specValue = $specValues[$i] ?? null;
    //         $specDesc = $specDescriptions[$i] ?? null;

    //         if ($specValue === null && $specDesc === null) {
    //             continue;
    //         }

    //         if (!$specId || !$specType) {
    //             continue; // skip jika datanya tidak lengkap
    //         }

    //         $pivot[$specId] = [
    //             'value' => null,
    //             'value_desc' => null,
    //             'value_bool' => null
    //         ];

    //         switch ($specType) {
    //             case 'availability':
    //                 $pivot[$specId]['value_bool'] = filter_var($specValue, FILTER_VALIDATE_BOOLEAN);
    //                 $pivot[$specId]['value_desc'] = $specDesc;
    //                 break;
    //             case 'list':
    //                 $lists = $request->input($specValue); // pastikan ini juga aman
    //                 $pivot[$specId]['value_desc'] = $specDesc;
    //                 $pivotLists[] = [
    //                     'specId' => $specId,
    //                     'lists' => $lists
    //                 ];
    //                 break;
    //             default:
    //                 $pivot[$specId]['value'] = $specValue;
    //                 $pivot[$specId]['value_desc'] = $specDesc;
    //                 break;
    //         }
    //     }

    //     $vehicle->specs()->attach($pivot);

    //     if (!empty($pivotLists)) {
    //         foreach ($pivotLists as $list) {
    //             // Validasi array agar elemen 'specId' dan 'lists' tersedia
    //             if (!isset($list['specId'], $list['lists'])) {
    //                 continue;
    //             }

    //             // Cari relasi pivot spec_vehicle berdasarkan spec ID
    //             $specVehicle = $vehicle->specs()->where('specs.id', $list['specId'])->first();

    //             // Jika relasi ditemukan dan memiliki pivot
    //             if ($specVehicle && $specVehicle->pivot) {
    //                 $specVehicleId = $specVehicle->pivot->id;

    //                 // Temukan spec_vehicle, lalu attach ke relasi lists
    //                 $specVehicleModel = SpecVehicle::find($specVehicleId);
    //                 if ($specVehicleModel) {
    //                     $specVehicleModel->lists()->attach($list['lists']);
    //                 }
    //             }
    //         }
    //     }

    //     // Upload pictures
    //     if ($request->hasFile('pictures')) {
    //         $pictures = collect($request->file('pictures'))->sortBy(function ($file) {
    //             return $file->getClientOriginalName();
    //         })->values();

    //         $pics = [];
    //         foreach ($pictures as $i => $img) {
    //             $pics[] = [
    //                 'path' => $img->store('vehicle', 'public'),
    //                 'thumbnail' => $i === 0 ? true : false,
    //             ];
    //         }

    //         $vehicle->pictures()->createMany($pics);
    //     }

    //     return response()->json([
    //         'message' => 'Vehicle created successfully',
    //         'vehicle' => $vehicle->load('type', 'brand', 'specs', 'pictures')
    //     ], 201);
    // }

    // public function store(Request $request)
    // {
    //     Log::info('Incoming vehicle store request', ['request_data' => $request->all()]);

    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'type' => 'required|exists:types,id',
    //         'brand' => 'required|exists:brands,id',
    //         'spec_ids' => 'array',
    //         'value_types' => 'array',
    //         'values' => 'array',
    //         'value_descriptions' => 'array',
    //         'pictures.*' => 'file|image|max:2048',
    //     ]);

    //     if ($validator->fails()) {
    //         Log::warning('Validation failed for vehicle store', ['errors' => $validator->errors(), 'request_data' => $request->all()]);
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     try {
    //         $type = Type::find($request->type);
    //         $brand = Brand::find($request->brand);

    //         $vehicle = new Vehicle;
    //         $vehicle->name = $request->name;
    //         $vehicle->type()->associate($type);
    //         $vehicle->brand()->associate($brand);
    //         $vehicle->save();

    //         Log::info('Vehicle created', ['vehicle_id' => $vehicle->id]);

    //         // Process specs
    //         $specIds = $request->spec_ids ?? [];
    //         $specTypes = $request->value_types ?? [];
    //         $specValues = $request->values ?? [];
    //         $specDescriptions = $request->value_descriptions ?? [];

    //         $pivot = [];
    //         $pivotLists = [];

    //         for ($i = 0; $i < count($specValues); $i++) {
    //             $specId = $specIds[$i] ?? null;
    //             $specType = $specTypes[$i] ?? null;
    //             $specValue = $specValues[$i] ?? null;
    //             $specDesc = $specDescriptions[$i] ?? null;

    //             if ($specValue === null && $specDesc === null) {
    //                 continue;
    //             }

    //             if (!$specId || !$specType) {
    //                 continue; // skip jika datanya tidak lengkap
    //             }

    //             $pivot[$specId] = [
    //                 'value' => null,
    //                 'value_desc' => null,
    //                 'value_bool' => null
    //             ];

    //             switch ($specType) {
    //                 case 'availability':
    //                     $pivot[$specId]['value_bool'] = filter_var($specValue, FILTER_VALIDATE_BOOLEAN);
    //                     $pivot[$specId]['value_desc'] = $specDesc;
    //                     break;
    //                 case 'list':
    //                     $lists = $request->input($specValue);
    //                     $pivot[$specId]['value_desc'] = $specDesc;
    //                     $pivotLists[] = [
    //                         'specId' => $specId,
    //                         'lists' => $lists
    //                     ];
    //                     break;
    //                 default:
    //                     $pivot[$specId]['value'] = $specValue;
    //                     $pivot[$specId]['value_desc'] = $specDesc;
    //                     break;
    //             }
    //         }

    //         Log::info('Specs processed for vehicle', ['vehicle_id' => $vehicle->id, 'pivot_data' => $pivot, 'pivot_lists' => $pivotLists]);

    //         $vehicle->specs()->attach($pivot);

    //         if (!empty($pivotLists)) {
    //             foreach ($pivotLists as $list) {
    //                 if (!isset($list['specId'], $list['lists'])) {
    //                     continue;
    //                 }

    //                 $specVehicle = $vehicle->specs()->where('specs.id', $list['specId'])->first();

    //                 if ($specVehicle && $specVehicle->pivot) {
    //                     $specVehicleId = $specVehicle->pivot->id;

    //                     $specVehicleModel = SpecVehicle::find($specVehicleId);
    //                     if ($specVehicleModel) {
    //                         $specVehicleModel->lists()->attach($list['lists']);
    //                     }
    //                 }
    //             }
    //         }

    //         // Upload pictures
    //         if ($request->hasFile('pictures')) {
    //             $pictures = collect($request->file('pictures'))->sortBy(function ($file) {
    //                 return $file->getClientOriginalName();
    //             })->values();

    //             $pics = [];
    //             foreach ($pictures as $i => $img) {
    //                 $pics[] = [
    //                     'path' => $img->store('vehicle', 'public'),
    //                     'thumbnail' => $i === 0 ? true : false,
    //                 ];
    //             }

    //             $vehicle->pictures()->createMany($pics);

    //             Log::info('Pictures uploaded', ['vehicle_id' => $vehicle->id, 'pictures_count' => count($pics)]);
    //         }

    //         return response()->json([
    //             'message' => 'Vehicle created successfully',
    //             'vehicle' => $vehicle->load('type', 'brand', 'specs', 'pictures')
    //         ], 201);

    //     } catch (\Exception $e) {
    //         Log::error('Error storing vehicle', [
    //             'error_message' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString(),
    //             'request_data' => $request->all()
    //         ]);

    //         return response()->json(['error' => 'Failed to create vehicle.'], 500);
    //     }
    // }

    public function store(Request $request)
    {
        Log::info('Incoming vehicle store request', ['request_data' => $request->all()]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|exists:types,id',
            'brand' => 'required|exists:brands,id',
            'spec_ids' => 'array',
            'value_types' => 'array',
            'values' => 'array',
            'value_descriptions' => 'array',
            'pictures.*' => 'file|image|max:2048',
        ]);

        $valueBools = $request->input('value_bool', []);

        if ($validator->fails()) {
            Log::warning('Validation failed for vehicle store', ['errors' => $validator->errors()]);
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $type = Type::find($request->type);
            $brand = Brand::find($request->brand);

            $vehicle = new Vehicle();
            $vehicle->name = $request->name;
            $vehicle->type()->associate($type);
            $vehicle->brand()->associate($brand);
            $vehicle->save();

            Log::info('Vehicle created', ['vehicle_id' => $vehicle->id]);

            // Handle specs
            $specIds = $request->spec_ids ?? [];
            $specTypes = $request->value_types ?? [];
            $specValues = $request->values ?? [];
            $specDescriptions = $request->value_descriptions ?? [];

            $pivot = [];
            $pivotLists = [];

            // for ($i = 0; $i < count($specIds); $i++) {
            //     $specId = $specIds[$i];
            //     $specType = $specTypes[$i] ?? null;
            //     $specValue = $specValues[$i] ?? null;
            //     $specDesc = $specDescriptions[$i] ?? null;

            //     if ($specValue === null && $specDesc === null) {
            //         continue;
            //     }

            //     if (!$specId || !$specType) {
            //         continue;
            //     }

            //     $pivot[$specId] = [
            //         'value' => null,
            //         'value_desc' => null,
            //         'value_bool' => null
            //     ];

            //     switch ($specType) {
            //         case 'availability':
            //             $pivot[$specId]['value_bool'] = filter_var($specValue, FILTER_VALIDATE_BOOLEAN);
            //             $pivot[$specId]['value_desc'] = $specDesc;
            //             break;

            //         case 'list':
            //             $listKey = "list_values_{$specId}";
            //             $lists = $request->input($listKey, []);
            //             $pivot[$specId]['value_desc'] = $specDesc;
            //             $pivotLists[] = [
            //                 'specId' => $specId,
            //                 'lists' => $lists
            //             ];
            //             break;

            //         default:
            //             $pivot[$specId]['value'] = $specValue;
            //             $pivot[$specId]['value_desc'] = $specDesc;
            //             break;
            //     }
            // }

            for ($i = 0; $i < count($specIds); $i++) {
                $specId = $specIds[$i];
                $specType = $specTypes[$i] ?? null;
                $specValue = $specValues[$i] ?? null;
                $specDesc = $specDescriptions[$i] ?? null;

                if ($specValue === null && $specDesc === null && !isset($valueBools[$specId])) {
                    continue;
                }

                if (!$specId || !$specType) {
                    continue;
                }

                $pivot[$specId] = [
                    'value' => null,
                    'value_desc' => null,
                    'value_bool' => null,
                ];

                switch ($specType) {
                    case 'availability':
                        // Gunakan value_bool jika ada
                        $boolValue = $valueBools[$specId] ?? $specValue;
                        $pivot[$specId]['value_bool'] = filter_var($boolValue, FILTER_VALIDATE_BOOLEAN);
                        $pivot[$specId]['value_desc'] = $specDesc;
                        break;

                    case 'list':
                        $listKey = "list_values_{$specId}";
                        $lists = $request->input($listKey, []);
                        $pivot[$specId]['value_desc'] = $specDesc;
                        $pivotLists[] = [
                            'specId' => $specId,
                            'lists' => $lists,
                        ];
                        break;

                    default:
                        $pivot[$specId]['value'] = $specValue;
                        $pivot[$specId]['value_desc'] = $specDesc;
                        break;
                }
            }

            $vehicle->specs()->attach($pivot);

            // Hubungkan list spesifik
            foreach ($pivotLists as $list) {
                $specVehicle = $vehicle->specs()->where('specs.id', $list['specId'])->first();
                if ($specVehicle && $specVehicle->pivot) {
                    $specVehicleId = $specVehicle->pivot->id;
                    $specVehicleModel = SpecVehicle::find($specVehicleId);
                    if ($specVehicleModel) {
                        $specVehicleModel->lists()->attach($list['lists']);
                    }
                }
            }

            // Upload only 1 picture (single file expected)
            if ($request->hasFile('pictures')) {
                $file = $request->file('pictures');

                // Jika secara tidak sengaja dikirim array, ambil yang pertama
                if (is_array($file)) {
                    $file = collect($file)
                        ->filter() // Hilangkan null/false
                        ->sortBy(function ($file) {
                            return $file->getClientOriginalName();
                        })
                        ->first();
                }

                // Pastikan file valid sebelum diproses
                if ($file && $file->isValid()) {
                    $path = $file->store('vehicle', 'public');

                    $vehicle->pictures()->create([
                        'path' => $path,
                        'thumbnail' => true,
                    ]);

                    Log::info('Single picture uploaded', [
                        'vehicle_id' => $vehicle->id,
                        'path' => $path,
                    ]);
                }
            }

            return response()->json(
                [
                    'message' => 'Vehicle created successfully',
                    'vehicle' => $vehicle->load('type', 'brand', 'specs.lists', 'pictures'),
                ],
                201,
            );
        } catch (\Exception $e) {
            Log::error('Error storing vehicle', [
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['error' => 'Failed to create vehicle.'], 500);
        }
    }
}

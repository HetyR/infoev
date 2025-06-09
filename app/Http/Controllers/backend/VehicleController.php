<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Spec;
use App\Models\SpecCategory;
use App\Models\SpecList;
use App\Models\SpecVehicle;
use App\Models\Type;
use App\Models\Vehicle;
use App\Models\Marketplace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Contract\Messaging;

class VehicleController extends Controller
{
    protected Messaging $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

    public function index(Request $request)
    {
        $typeId = $request->input('type_id');
        $brandId = $request->input('brand_id');
        $marketplaceId = $request->input('marketplace_id');

        // Query kendaraan
        $query = Vehicle::query();

        if ($typeId) {
            $query->where('type_id', $typeId);
        }

        if ($brandId) {
            $query->where('brand_id', $brandId);
        }

        if ($marketplaceId === 'none') {
            $query->whereDoesntHave('affiliate');
        } elseif ($marketplaceId) {
            $query->whereHas('affiliate', function ($q) use ($marketplaceId) {
                $q->where('marketplace_id', $marketplaceId);
            });
        }

        $vehicles = $query->get();

        $filteredBrands = Brand::whereHas('vehicles', function ($query) use ($typeId) {
            if ($typeId) {
                $query->where('type_id', $typeId);
            }
        })->get();

        return view('backend.vehicle.index', [
            'vehicles' => $vehicles,
            'types' => Type::all(),
            'brands' => $filteredBrands,
            'marketplaces' => Marketplace::all(),
        ]);
    }

    public function create()
    {
        return view('backend.vehicle.create', [
            'types' => Type::orderBy('name')->get(),
            'brands' => Brand::orderBy('name')->get(),
            'specs' => SpecCategory::with('specs', 'specs.lists')->orderBy('priority')->get(),
        ]);
    }

    public function store(Request $request)
    {
        // $validated = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'type' => 'required|exists:types,id',
        //     'brand' => 'required|exists:brands,id',

        //     'spec_ids' => 'required|array',
        //     'spec_ids.*' => 'required|exists:specs,id',

        //     'value_types' => 'required|array',
        //     'value_types.*' => 'required|string|in:price,unit,description,list,availability',

        //     'values' => 'required|array',
        //     'values.*' => 'nullable|string',

        //     'value_descriptions' => 'nullable|array',
        //     'value_descriptions.*' => 'nullable|string',
        // ]);

        $typeId = $request->type;
        $brandId = $request->brand;

        $type = Type::find($typeId);
        $brand = Brand::find($brandId);

        $vehicle = new Vehicle();
        $vehicle->name = $request->name;

        $vehicle->type()->associate($type);
        $vehicle->brand()->associate($brand);
        $vehicle->save();

        // dd($vehicle->type, $vehicle->brand);

        $specIds = $request->spec_ids;
        $specTypes = $request->value_types;
        $specValues = $request->values;
        $specDescriptions = $request->value_descriptions;
        $pivot = [];
        $pivotLists = [];
        if (is_array($specValues)) {
            for ($i = 0; $i < count($specValues); $i++) {
                if ($specValues[$i] == null && $specDescriptions[$i] == null) {
                    continue;
                }

                $pivot[$specIds[$i]] = [
                    'value' => null,
                    'value_desc' => null,
                    'value_bool' => null,
                ];
                $lists = null;

                switch ($specTypes[$i]) {
                    case 'availability':
                        $pivot[$specIds[$i]]['value_bool'] = filter_var($specValues[$i], FILTER_VALIDATE_BOOLEAN);
                        $pivot[$specIds[$i]]['value_desc'] = $specDescriptions[$i];
                        break;
                    case 'list':
                        $lists = $request->input($specValues[$i]);
                        $pivot[$specIds[$i]]['value_desc'] = $specDescriptions[$i];
                        break;
                    default:
                        $pivot[$specIds[$i]]['value'] = $specValues[$i];
                        $pivot[$specIds[$i]]['value_desc'] = $specDescriptions[$i];
                        break;
                }

                if ($specTypes[$i] == 'list') {
                    array_push($pivotLists, [
                        'specId' => $specIds[$i],
                        'lists' => $lists,
                    ]);
                }
            }
        }

        $vehicle->specs()->attach($pivot);
        foreach ($pivotLists as $list) {
            $specVehicleId = $vehicle->specs()->where('specs.id', $list['specId'])->first()->pivot->id;
            SpecVehicle::find($specVehicleId)->lists()->attach($list['lists']);
        }

        // die();
        // dd(Vehicle::find(73)->specs()->where('specs.id', 8)->first()->pivot);
        // $id = Vehicle::find(73)->specs()->where('specs.id', 8)->first()->pivot->id;
        // dd(SpecList::find(2)->specVehicles()->attach($id));
        // dd(Vehicle::find(73)->specs()->where('specs.id', 8)->first()->pivot->id);
        // dd(Vehicle::find(73)->specs[0]->pivot->lists);
        // dd(SpecVehicle::where('vehicle_id', 73)->where('spec_id', 8)->lists()->attach(2));

        // $specifications = $request->specifications;
        // $specValues = $request->specValues;
        // for ($i = 0; $i < 4; $i++) {
        //     if ($specValues[$i] == null) {
        //         unset($specifications[$i]);
        //         unset($specValues[$i]);
        //     }
        // }
        // $specifications = array_values($specifications);
        // $specValues = array_values($specValues);

        // if (!empty($specifications) && !empty($specValues)) {
        //     $pivot = [];
        //     for ($i=0; $i < count($specValues); $i++) {
        //         $specId = $specifications[$i];
        //         $val = $specValues[$i];

        //         if (!is_null($specId) && !is_null($val)) {
        //             $pivot[$specId] = ['value' => $val];
        //         }
        //     }

        //     $vehicle->specs()->attach($pivot);
        // }

        // Simpan gambar
        $imageUrl = null;
        if ($request->hasFile('pictures')) {
            $pictures = collect($request->file('pictures'))->sortBy->getClientOriginalName()->values();

            $pics = [];
            foreach ($pictures as $i => $img) {
                $path = $img->store('vehicle', 'public');
                $pics[] = [
                    'path' => $path,
                    'thumbnail' => $i === 0,
                ];

                if ($i === 0) {
                    $imageUrl = Storage::disk('public')->url($path);
                }
            }
            $vehicle->pictures()->createMany($pics);
        }

        // Kirim notifikasi FCM
        $message = CloudMessage::fromArray([
            'topic' => 'infoev_vehicle',
            'notification' => [
                'title' => "Baru! {$vehicle->name} - {$vehicle->brand->name}",
                'body' => "Lihat {$vehicle->type->name} terbaru dari {$vehicle->brand->name}. Klik untuk info selengkapnya!",
                'image' => $imageUrl ?? '',
            ],
            'data' => [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'vehicle_id' => (string) $vehicle->id,
                'name' => $vehicle->name,
                'brand' => $vehicle->brand->name,
                'type' => $vehicle->type->name,
                'image' => $imageUrl ?? '',
            ],
        ]);

        $this->messaging->send($message);

        return redirect()->route('backend.vehicle.index');
    }

    public function edit(Vehicle $vehicle)
    {
        return view('backend.vehicle.edit', [
            'types' => Type::orderBy('name')->get(),
            'brands' => Brand::orderBy('name')->get(),
            'specs' => SpecCategory::with('specs', 'specs.lists')->orderBy('name')->get(),
            // 'hiddenSpecs' => Spec::with(['vehicles' => function ($query) use ($vehicle) {
            //                     $query->where('vehicles.id', $vehicle->id);
            //                 }])
            //             ->where('hidden', true)
            //             ->get(),
            'vehicle' => $vehicle,
        ]);
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $typeId = $request->type;
        $brandId = $request->brand;

        $type = Type::find($typeId);
        $brand = Brand::find($brandId);

        $vehicle->name = $request->name;
        // $vehicle->price = $request->price;
        // $vehicle->year = $request->year;

        $vehicle->type()->associate($type);
        $vehicle->brand()->associate($brand);
        $vehicle->save();

        $specIds = $request->spec_ids;
        $specTypes = $request->value_types;
        $specValues = $request->values;
        $specDescriptions = $request->value_descriptions;
        $pivot = [];
        $pivotLists = [];
        if ($specValues == null || (count($specValues) == 1 && ($specValues[0] == null && $specDescriptions[0] == null))) {
            $vehicle->specs()->detach();
        } else {
            for ($i = 0; $i < count($specValues); $i++) {
                if ($specValues[$i] == null && $specDescriptions[$i] == null) {
                    continue;
                }

                $pivot[$specIds[$i]] = [
                    'value' => null,
                    'value_desc' => null,
                    'value_bool' => null,
                ];
                $lists = null;

                switch ($specTypes[$i]) {
                    case 'availability':
                        $pivot[$specIds[$i]]['value_bool'] = filter_var($specValues[$i], FILTER_VALIDATE_BOOLEAN);
                        $pivot[$specIds[$i]]['value_desc'] = $specDescriptions[$i];
                        break;
                    case 'list':
                        $lists = $request->input($specValues[$i]);
                        $pivot[$specIds[$i]]['value_desc'] = $specDescriptions[$i];
                        break;
                    default:
                        $pivot[$specIds[$i]]['value'] = $specValues[$i];
                        $pivot[$specIds[$i]]['value_desc'] = $specDescriptions[$i];
                        break;
                }

                if ($specTypes[$i] == 'list') {
                    array_push($pivotLists, [
                        'specId' => $specIds[$i],
                        'lists' => $lists,
                    ]);
                }
            }

            $vehicle->specs()->sync($pivot);
            foreach ($pivotLists as $list) {
                $specVehicleId = $vehicle->specs()->where('specs.id', $list['specId'])->first()->pivot->id;
                SpecVehicle::find($specVehicleId)->lists()->sync($list['lists']);
            }
        }

        // $specifications = $request->specifications;
        // $specValues = $request->specValues;
        // for ($i = 0; $i < 4; $i++) {
        //     if ($specValues[$i] == null) {
        //         unset($specifications[$i]);
        //         unset($specValues[$i]);
        //     }
        // }
        // $specifications = array_values($specifications);
        // $specValues = array_values($specValues);

        // if (empty($specifications) && empty($specValues)) {
        //     $vehicle->specs()->detach();
        // } else {
        //     $pivot = [];
        //     for ($i=0; $i < count($specValues); $i++) {
        //         $specId = $specifications[$i];
        //         $val = $specValues[$i];

        //         if (!is_null($val)) {
        //             $pivot[$specId] = ['value' => $val];
        //         }
        //     }

        //     $vehicle->specs()->sync($pivot);
        // }

        if ($request->hasFile('pictures')) {
            $del = $vehicle->pictures;
            foreach ($del as $img) {
                Storage::delete('public/' . $img->path);
                $img->delete();
            }

            $pictures = collect($request->file('pictures'));
            $pictures = $pictures
                ->sortBy(function ($file) {
                    return $file->getClientOriginalName();
                })
                ->values();

            $pics = [];
            foreach ($pictures as $i => $img) {
                array_push($pics, [
                    'path' => $img->store('vehicle', 'public'),
                    'thumbnail' => $i === 0 ? true : false,
                ]);
            }

            $vehicle->pictures()->createMany($pics);
        }

        return redirect()->route('backend.vehicle.index');
    }

    public function destroy(Vehicle $vehicle)
    {
        $pictures = $vehicle->pictures;
        if ($pictures->count() > 0) {
            foreach ($pictures as $pic) {
                Storage::delete('public/' . $pic->path);
                $pic->delete();
            }
        }

        $vehicle->delete();
        return redirect()->route('backend.vehicle.index');
    }
}
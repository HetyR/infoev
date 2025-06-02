<?php

namespace App\Http\Controllers\Api;
use App\Models\Vehicle;
use App\Models\SpecCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ListController extends Controller
{
  
    // public function show($vehicleId)
    // {
    //     // Cari kendaraan berdasarkan ID
    //     $vehicle = Vehicle::findOrFail($vehicleId);
    
    //     // Ambil data menggunakan join tabel dengan nama tabel yang benar
    //     $lists = DB::table('spec_lists')
    //         ->join('spec_list_spec_vehicle', 'spec_lists.id', '=', 'spec_list_spec_vehicle.spec_list_id')
    //         ->join('spec_vehicle', 'spec_vehicle.id', '=', 'spec_list_spec_vehicle.spec_vehicle_id')
    //         ->where('spec_vehicle.vehicle_id', $vehicleId)
    //         ->whereIn('spec_vehicle.spec_id', [10, 17, 23])
    //         ->select('spec_lists.*')
    //         ->get();
    
    //     // Debugging: periksa data yang diteruskan ke view
    //     dd($vehicle, $lists);
    
    //     // Tampilkan view dengan data yang diperlukan
    //     return view('vehicle_list.index', [
    //         'vehicle' => $vehicle,
    //         'lists' => $lists,
    //     ]);
    // }

    public function show($slug, Request $request)
    {
        // Cari kendaraan berdasarkan slug
        $vehicle = Vehicle::where('slug', $slug)->firstOrFail();
    
        // Ambil data menggunakan join tabel dengan nama tabel yang benar
        $specs = DB::table('spec_lists')
            ->join('spec_list_spec_vehicle', 'spec_lists.id', '=', 'spec_list_spec_vehicle.spec_list_id')
            ->join('spec_vehicle', 'spec_vehicle.id', '=', 'spec_list_spec_vehicle.spec_vehicle_id')
            ->join('specs', 'specs.id', '=', 'spec_lists.spec_id') // Join dengan tabel specs
            ->join('spec_categories', 'specs.spec_category_id', '=', 'spec_categories.id') // Join dengan tabel spec_categories
            ->where('spec_vehicle.vehicle_id', $vehicle->id)
            ->whereIn('spec_vehicle.spec_id', [10, 17, 23, 46]) // Contoh filter spec_id yang diinginkan
            ->select('spec_lists.*', 'specs.name as spec_name', 'specs.spec_category_id', 'spec_categories.name as category_name') // Pilih kolom yang dibutuhkan, termasuk nama spec, id kategori, dan nama kategori
            ->distinct()
            ->get();
    
        // Kelompokkan spesifikasi berdasarkan kategori dan spec_name
        $groupedSpecs = $specs->groupBy(['spec_category_id', 'spec_name']);
    
        // Gabungkan nilai list menjadi satu string yang dipisahkan oleh koma
        $formattedSpecs = $groupedSpecs->map(function ($itemsBySpecName, $categoryId) {
            $categoryName = $itemsBySpecName->first()->first()->category_name; // Nama kategori
            return $itemsBySpecName->map(function ($items, $specName) use ($categoryId, $categoryName) {
                $lists = $items->pluck('list')->implode(', '); // Gabungkan nilai list
                return [
                    'category_id' => $categoryId,
                    'category_name' => $categoryName,
                    'spec_name' => $specName,
                    'list' => $lists,
                ];
            });
        })->flatten(1)->values();
    
        // Debugging: periksa data yang diteruskan ke view
        // dd($vehicle, $formattedSpecs);
    
        // Mengembalikan response dalam format JSON
        return response()->json([
            'vehicle' => $vehicle,
            'specs' => $formattedSpecs,
        ]);
    }
    

    }
    


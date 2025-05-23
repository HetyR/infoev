<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Option;
use App\Models\Spec;
use App\Models\Type;
use App\Models\Vehicle;
use Illuminate\Contracts\Database\Eloquent\Builder;
class TypeController extends Controller
{
    //Update untuk testing
    public function index() {
        // Ambil data banner jika diperlukan
        $banner = Option::where([
            ['type', 'banner'],
            ['name', 'type']
        ])->with('thumbnail')->first();

        // Ambil data tipe kendaraan dengan slug, id, dan name, serta hitungan kendaraan yang memiliki kendaraan terkait
        $types = Type::orderBy('name')
                     ->withCount('vehicles')
                     ->having('vehicles_count', '>', 0)
                     ->get(['id', 'name', 'slug']); // Hanya mengambil kolom yang diperlukan

        // Menyusun data untuk respons JSON
        $data = [
            'items' => $types,
        ];

        return response()->json($data);
    }






    public function show(Type $type) {
        $getImageUrl = function ($image) {
            return $image ? asset('storage/' . $image->path) : null;
        };

        // $vehicles = Spec::find(1)
        $vehicles = Spec::first()
                        ->vehicles()
                        ->where('type_id', $type->id)
                        ->orderByPivot('value', 'desc')
                        ->get();

        $vehicles->each(function($vehicle) use ($getImageUrl) {
            $firstPicture = $vehicle->pictures->first();
            $vehicle->thumbnail_url = $firstPicture ? $getImageUrl($firstPicture) : null;
        });

        $data = [
            'vehicles' => $vehicles,
            'title' => 'Daftar ' . $type->name . ' Listrik',
            'banner' => $type->thumbnail,
            'logo' => Option::where('type', 'logo')->with('thumbnail')->first()
        ];

        return response()->json($data);
    }


 

}

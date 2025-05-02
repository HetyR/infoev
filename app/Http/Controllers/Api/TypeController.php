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






    // public function show(Type $type)
    // {
    //     // Ambil objek merek. Gantilah 1 dengan ID merek yang sesuai
    //     $brand = Brand::findOrFail(1); // Misalnya, mengambil Brand dengan ID 1

    //     $getImageUrl = function ($image) {
    //         return $image ? asset('storage/' . $image->path) : null;
    //     };

    //     // Memuat gambar dari URL atas untuk setiap kendaraan
    //     $vehicles = Spec::find(1)
    //                     ->vehicles()
    //                     ->where('brand_id', $brand->id)
    //                     ->where('type_id', $type->id)
    //                     ->orderByPivot('value', 'desc')
    //                     ->get(); // Mengambil semua kendaraan tanpa pagination

    //     // Memproses setiap kendaraan untuk mendapatkan thumbnail dan data spesifikasi
    //     $vehicles->transform(function($vehicle) use ($getImageUrl) {
    //         $firstPicture = $vehicle->pictures->first();
    //         $vehicle->thumbnail_url = $firstPicture ? $getImageUrl($firstPicture) : null;

    //         // Hanya ambil data yang diperlukan dari spesifikasi
    //         $vehicle->spec = [
    //             'spec_id' => $vehicle->pivot->spec_id,
    //             'value' => $vehicle->pivot->value,
    //         ];

    //         // Hapus data yang tidak diperlukan
    //         unset($vehicle->brand);
    //         unset($vehicle->pictures);
    //         unset($vehicle->pivot);

    //         return $vehicle;
    //     });

    //     // Ambil data tipe kendaraan
    //     $typeData = [
    //         'type_id' => $type->id,
    //         'type_name' => $type->slug,
    //         'name_brand' => $brand->name, // Hanya menampilkan nama merek
    //         'vehicles' => $vehicles,
    //     ];

    //     // Mengembalikan respons JSON dengan data tipe kendaraan dan kendaraan
    //     return response()->json($typeData);
    // }

    public function show(Type $type) {
        $getImageUrl = function ($image) {
            return $image ? asset('storage/' . $image->path) : null;
        };

        $vehicles = Spec::find(1)
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

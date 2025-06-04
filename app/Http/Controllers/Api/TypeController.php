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
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Database\Eloquent\Builder;

class TypeController extends Controller
{
    //Update untuk testing
    public function index()
    {
        // Ambil data banner jika diperlukan
        $banner = Option::where([['type', 'banner'], ['name', 'type']])
            ->with('thumbnail')
            ->first();

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

    public function show2($type)
    {
        $getImageUrl = function ($image) {
            return $image ? asset('storage/' . $image->path) : null;
        };

        // Jika input 'type' adalah 'all', ambil semua tipe
        if ($type === 'all') {
            $brands = Brand::with(['thumbnail', 'vehicles'])->get();
            $typeName = 'semua';
        } else {
            // Ambil tipe berdasarkan ID atau slug
            $typeModel = Type::where('slug', $type)->orWhere('id', $type)->first();

            if (!$typeModel) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Tipe kendaraan tidak ditemukan',
                    ],
                    404,
                );
            }

            $brands = Brand::with([
                'thumbnail',
                'vehicles' => function ($query) use ($typeModel) {
                    $query->where('type_id', $typeModel->id);
                },
            ])->get();

            $typeName = $typeModel->name;
        }

        // Format hasil
        $result = $brands
            ->map(function ($brand) use ($getImageUrl, $type) {
                $count = $type === 'semua' ? $brand->vehicles->count() : $brand->vehicles->filter(fn($v) => $v->type_id)->count();

                if ($count === 0) {
                    return null;
                }

                return [
                    'id' => $brand->id,
                    'slug' => $brand->slug,
                    'name_brand' => $brand->name,
                    'count_vehicle' => $count,
                    'banner' => $getImageUrl($brand->thumbnail),
                ];
            })
            ->filter()
            ->values(); // hilangkan null dan reset indeks

        return response()->json([
            'success' => true,
            'type' => $typeName,
            'message' => 'Data brand ' . ($type === 'all' ? 'semua kendaraan listrik' : 'kendaraan listrik untuk tipe ' . $typeName),
            'data' => $result,
        ]);
    }
    public function show(Type $type)
    {
        $getImageUrl = function ($image) {
            return $image ? asset('storage/' . $image->path) : null;
        };

        // $vehicles = Spec::find(1)
        $vehicles = Spec::first()->vehicles()->where('type_id', $type->id)->orderByPivot('value', 'desc')->get();

        $vehicles->each(function ($vehicle) use ($getImageUrl) {
            $firstPicture = $vehicle->pictures->first();
            $vehicle->thumbnail_url = $firstPicture ? $getImageUrl($firstPicture) : null;
        });

        $data = [
            'vehicles' => $vehicles,
            'title' => 'Daftar ' . $type->name . ' Listrik',
            'banner' => $type->thumbnail,
            'logo' => Option::where('type', 'logo')->with('thumbnail')->first(),
        ];

        return response()->json($data);
    }
}

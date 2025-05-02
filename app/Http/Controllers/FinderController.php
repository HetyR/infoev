<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Option;
use App\Models\Brand;
use App\Models\Blog;
use App\Models\Vehicle;
use App\Models\Type;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class FinderController extends Controller
{
    //
    public function getBrandsByType(Request $request)
    {
        $vehicleType = $request->input('vehicleType');

        $brands = Brand::whereHas('vehicles.type', function (Builder $query) use ($vehicleType) {
            $query->where('id', $vehicleType);
        })
            ->withCount('vehicles')
            ->having('vehicles_count', '>', 0)
            ->orderBy('vehicles_count', 'desc')
            ->get();

        return response()->json($brands);
    }
    public function index(Request $request)
    {
        $logo = Option::where('type', 'logo')->with('thumbnail')->first();
        $bikeBrands = Brand::limit(14)
            ->whereHas('vehicles.type', function (Builder $query) {
                $query->where('name', 'sepeda motor');
            })
            ->withCount('vehicles')
            ->having('vehicles_count', '>', 0)
            ->orderBy('vehicles_count', 'desc')
            ->get();

        $carBrands = Brand::limit(14)
            ->whereHas('vehicles.type', function (Builder $query) {
                $query->where('name', 'mobil');
            })
            ->withCount('vehicles')
            ->having('vehicles_count', '>', 0)
            ->orderBy('vehicles_count', 'desc')
            ->get();

        $sepedaBrands = Brand::limit(14)
            ->whereHas('vehicles.type', function (Builder $query) {
                $query->where('name', 'sepeda');
            })
            ->withCount('vehicles')
            ->having('vehicles_count', '>', 0)
            ->orderBy('vehicles_count', 'desc')
            ->get();

        $skuterBrands = Brand::limit(14)
            ->whereHas('vehicles.type', function (Builder $query) {
                $query->where('name', 'skuter');
            })
            ->withCount('vehicles')
            ->having('vehicles_count', '>', 0)
            ->orderBy('vehicles_count', 'desc')
            ->get();

        $banner = Option::where('type', 'banner')->with('thumbnail')->first();
        $stickies = Blog::with('thumbnail')
            ->select('sticky_articles.*', 'blogs.*')
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

        // Query to get data from spec_vehicle where spec_id is 1
        $years = DB::table('spec_vehicle')->where('spec_id', 1)->get();

        //Mengambil sistem penggerak
        $driveSystems = DB::table('spec_lists')
            ->where('spec_id', 46)
            ->get();

        //Mengambil teknologi baterai
        $teknologibaterai = DB::table('spec_lists')
            ->where('spec_id', 1)
            ->get();

        // Ambil data dari tabel types
        $vehicleTypes = DB::table('types')->get();
        // dd($vehicleTypes); // Debug statement untuk memastikan data ada



        return view('finder.index', [
            'logo' => $logo,
            'bikeBrands' => $bikeBrands,
            'carBrands' => $carBrands,
            'sepedaBrands' => $sepedaBrands,
            'skuterBrands' => $skuterBrands,
            'banner' => is_null($banner) || is_null($banner->thumbnail) ? null : $banner->thumbnail,
            'recentVehicles' => Vehicle::with('brand')->latest()->limit(8)->get(),
            'popularVehicles' => Vehicle::with('brand')
                ->whereHas('views', fn (Builder $query) => $query->where('created_at', '>', now()->subMonths(3)))
                ->withCount('views')
                ->orderBy('views_count', 'desc')
                ->limit(10)
                ->get(),
            'stickies' => $stickies,
            'years' => $years,
            'driveSystems' => $driveSystems,
            'teknologibaterai' => $teknologibaterai,
            'vehicleTypes' => $vehicleTypes,
            // Update this line
            // 'vehicles' => $vehicles,
        ]);
    }


    public function search(Request $request)
    {
        // Define the $logo variable
        $logo = Option::where('type', 'logo')->with('thumbnail')->first();

        // Define other variables as needed
        $bikeBrands = Brand::limit(14)
            ->whereHas('vehicles.type', function (Builder $query) {
                $query->where('name', 'sepeda motor');
            })
            ->withCount('vehicles')
            ->having('vehicles_count', '>', 0)
            ->orderBy('vehicles_count', 'desc')
            ->get();
        $carBrands = Brand::limit(14)
            ->whereHas('vehicles.type', function (Builder $query) {
                $query->where('name', 'mobil');
            })
            ->withCount('vehicles')
            ->having('vehicles_count', '>', 0)
            ->orderBy('vehicles_count', 'desc')
            ->get();
        $banner = Option::where('type', 'banner')->with('thumbnail')->first();
        $stickies = Blog::with('thumbnail')
            ->select('sticky_articles.*', 'blogs.*')
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
        $years = DB::table('spec_vehicle')->where('spec_id', 1)->get();
        $driveSystems = DB::table('spec_lists')->where('spec_id', 46)->get();
        $teknologibaterai = DB::table('spec_lists')->where('spec_id', 10)->get();
        // Mengambil type dari tabel types
        $vehicleTypes = DB::table('types')->get(['id', 'name']);



        // $driveSystems = DB::table('spec_lists')->where('spec_id', 46)->pluck('list');

        $vehicles = Vehicle::query()


            ->when($request->input('vehicleType'), function ($query, $vehicleType) {
                return $query->where('type_id', $vehicleType);
            })


            ->when($request->input('brands'), function ($query, $brands) {
                return $query->whereIn('brand_id', $brands);
            })

            ->when($request->input('years'), function ($query, $years) {
                // Ambil nilai tahun dari tabel spec_vehicle berdasarkan spec_id = 1
                $yearValues = DB::table('spec_vehicle')
                    ->where('spec_id', 1)
                    ->whereIn('value', $years)
                    ->pluck('vehicle_id');

                // Filter kendaraan berdasarkan vehicle_id yang ada di $yearValues
                return $query->whereIn('id', $yearValues);
            })


            //Filter untuk driveSystems
            ->when($request->input('driveSystems'), function ($query, $driveSystems) {
                //relasi 3 tabel

                $vehicleIds = DB::table('spec_vehicle as sv')
                    ->join('spec_list_spec_vehicle as slsv', 'sv.id', '=', 'slsv.spec_vehicle_id')
                    ->join('spec_lists as sl', 'sl.id', '=', 'slsv.spec_list_id')
                    ->where('sv.spec_id', 46)
                    ->whereIn('sl.list', $driveSystems)
                    ->pluck('sv.vehicle_id');

                return $query->whereIn('id', $vehicleIds);
            
                //Memfilter spec_id 46 dan list yang sesuai dengan driveSystems.
                //Mengambil vehicle_id yang memenuhi kriteria.
            })

            ->when($request->input('teknologibaterai'), function ($query, $teknologibaterai) {
                $vehicleIds = DB::table('spec_vehicle as sv')
                    ->join('spec_list_spec_vehicle as slsv', 'sv.id', '=', 'slsv.spec_vehicle_id')
                    ->join('spec_lists as sl', 'sl.id', '=', 'slsv.spec_list_id')
                    ->where('sv.spec_id', 10)
                    ->whereIn('sl.list', $teknologibaterai)
                    ->pluck('sv.vehicle_id');

                return $query->whereIn('id', $vehicleIds);
            }) // Add this closing parenthesis

            //untuk slider harga
            ->when($request->input('price'), function ($query, $price) {
                // Filter kendaraan berdasarkan harga
                $vehicleIds = DB::table('spec_vehicle')
                    ->where('spec_id', 2) // Sesuaikan dengan spec_id untuk kolom harga
                    ->where('value', '<=', $price) // Sesuaikan dengan nama kolom yang menyimpan harga
                    ->pluck('vehicle_id');
                //pluck mengambil nilai dari kolom tertentu

                return $query->whereIn('id', $vehicleIds);
            })

            //untuk slider kecepatan
            ->when($request->input('maxspeed'), function ($query, $maxspeed) {

                // Filter kendaraan berdasarkan kecepatan
                $vehicleIds = DB::table('spec_vehicle')
                    ->where('spec_id', 6) // Sesuaikan dengan spec_id untuk kolom kecepatan
                    ->where('value', '<=', $maxspeed) // Sesuaikan dengan nama kolom yang menyimpan kecepatan
                    ->pluck('vehicle_id');

                return $query->whereIn('id', $vehicleIds);
            })


            //untuk slider baterai masi blm bisa
            ->when($request->input('batterykapasitas'), function ($query, $batterykapasitas) {

                // Filter kendaraan berdasarkan baterai
                $vehicleIds = DB::table('spec_vehicle')
                    ->where('spec_id', 8) // Sesuaikan dengan spec_id untuk kolom baterai
                    ->where('value', '<=', $batterykapasitas) // Sesuaikan dengan nama kolom yang menyimpan baterai
                    ->pluck('vehicle_id');

                return $query->whereIn('id', $vehicleIds);
            })



            //untuk slider pengecasan
            ->when($request->input('chargingtime'), function ($query, $chargingtime) {

                // Filter kendaraan berdasarkan pengecasan
                $vehicleIds = DB::table('spec_vehicle')
                    ->where('spec_id', 14) // Sesuaikan dengan spec_id untuk kolom pengecasan
                    ->where('value', '<=', $chargingtime) // Sesuaikan dengan nama kolom yang menyimpan pengecasan
                    ->pluck('vehicle_id');

                return $query->whereIn('id', $vehicleIds);
            })
            //untuk slider jarak tempuh
            ->when($request->input('distance'), function ($query, $distance) {

                // Filter kendaraan berdasarkan jarak tempuh
                $vehicleIds = DB::table('spec_vehicle')
                    ->where('spec_id', 11) // Sesuaikan dengan spec_id untuk kolom jarak tempuh
                    ->where('value', '<=', $distance) // Sesuaikan dengan nama kolom yang menyimpan jarak tempuh
                    ->pluck('vehicle_id');

                return $query->whereIn('id', $vehicleIds);
            })
            ->when($request->has('features') && in_array('alarm', $request->input('features')), function ($query) {
                // Filter kendaraan berdasarkan spesifikasi 'alarm'
                $vehicleIds = DB::table('spec_vehicle')
                    ->where('spec_id', 50) // Sesuaikan dengan spec_id untuk kolom alarm
                    ->where('value_bool', 1) // Sesuaikan dengan nama kolom yang menyimpan pengecasan
                    ->pluck('vehicle_id');

                return $query->whereIn('id', $vehicleIds);
            })
            ->when($request->has('features') && in_array('lampudepan', $request->input('features')), function ($query) {
                // Filter kendaraan berdasarkan spesifikasi 'alarm'
                $vehicleIds = DB::table('spec_vehicle')
                    ->where('spec_id', 43) // Sesuaikan dengan spec_id untuk kolom alarm
                    ->where('value_bool', 1) // Sesuaikan dengan nama kolom yang menyimpan pengecasan
                    ->pluck('vehicle_id');

                return $query->whereIn('id', $vehicleIds);
            })
            ->when($request->has('features') && in_array('lampubelakang', $request->input('features')), function ($query) {
                // Filter kendaraan berdasarkan spesifikasi 'alarm'
                $vehicleIds = DB::table('spec_vehicle')
                    ->where('spec_id', 44) // Sesuaikan dengan spec_id untuk kolom alarm
                    ->where('value_bool', 1) // Sesuaikan dengan nama kolom yang menyimpan pengecasan
                    ->pluck('vehicle_id');

                return $query->whereIn('id', $vehicleIds);
            })
            ->when($request->has('features') && in_array('lampuhazard', $request->input('features')), function ($query) {
                // Filter kendaraan berdasarkan spesifikasi 'alarm'
                $vehicleIds = DB::table('spec_vehicle')
                    ->where('spec_id', 45) // Sesuaikan dengan spec_id untuk kolom alarm
                    ->where('value_bool', 1) // Sesuaikan dengan nama kolom yang menyimpan pengecasan
                    ->pluck('vehicle_id');

                return $query->whereIn('id', $vehicleIds);
            })


            ->get();


        return view('finder.index', [
            'logo' => $logo,
            'bikeBrands' => $bikeBrands,
            'carBrands' => $carBrands,
            'banner' => is_null($banner) || is_null($banner->thumbnail) ? null : $banner->thumbnail,
            'recentVehicles' => Vehicle::with('brand')->latest()->limit(8)->get(),
            'popularVehicles' => Vehicle::with('brand')
                ->whereHas('views', fn (Builder $query) => $query->where('created_at', '>', now()->subMonths(3)))
                ->withCount('views')
                ->orderBy('views_count', 'desc')
                ->limit(10)
                ->get(),
            'stickies' => $stickies,
            'years' => $years,
            'driveSystems' => $driveSystems,
            'teknologibaterai' => $teknologibaterai,
            'vehicles' => $vehicles,
            'types' => $vehicleTypes,
            'vehicleTypes' => $vehicleTypes,  // Pastikan ini ada

        ]);
    }
}

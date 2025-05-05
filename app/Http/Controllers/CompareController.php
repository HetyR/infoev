<?php
namespace App\Http\Controllers;

use App\Models\Compare;
use App\Models\Brand;
use App\Models\Option;
use App\Models\Vehicle;
use App\Models\Blog;
use App\Models\SpecCategory;
use App\Models\Spec;
use Illuminate\Http\Request;
use Illuminate\Database\Query\Builder;

class CompareController extends Controller
{
    // Method untuk menampilkan halaman utama perbandingan
    public function index(Request $request)
    {
        // Mengambil logo dari tabel Option
        $logo = Option::where('type', 'logo')->with('thumbnail')->first();

        // Mengambil 14 merk sepeda motor yang memiliki kendaraan dan mengurutkannya berdasarkan jumlah kendaraan yang dimiliki
        $bikeBrands = Brand::limit(14)
            ->whereHas('vehicles.type', function (\Illuminate\Database\Eloquent\Builder $query) {
                $query->where('name', 'sepeda motor');
            })
            ->withCount('vehicles')
            ->having('vehicles_count', '>', 0)
            ->orderBy('vehicles_count', 'desc')
            ->get();

        // Mengambil 14 merk mobil yang memiliki kendaraan dan mengurutkannya berdasarkan jumlah kendaraan yang dimiliki
        $carBrands = Brand::limit(14)
            ->whereHas('vehicles.type', function (\Illuminate\Database\Eloquent\Builder $query) {
                $query->where('name', 'mobil');
            })
            ->withCount('vehicles')
            ->having('vehicles_count', '>', 0)
            ->orderBy('vehicles_count', 'desc')
            ->get();

        // Mengambil banner dari tabel Option
        $banner = Option::where('type', 'banner')->with('thumbnail')->first();

        // Mengambil artikel blog yang di-sticky
        $stickies = Blog::with('thumbnail')
            ->select('sticky_articles.*', 'blogs.*')
            ->join('sticky_articles', 'blogs.id', '=', 'sticky_articles.blog_id')
            ->where('blogs.published', true)
            ->orderBy('sticky_articles.created_at', 'desc')
            ->get();

        // Mengambil artikel blog yang ditandai sebagai unggulan
        $featured = Blog::with('thumbnail')
            ->latest()
            ->where('published', true)
            ->where('featured', true)
            ->limit(3)
            ->get();

        // Mengambil artikel blog tambahan jika artikel unggulan kurang dari 3
        $newsLimit = 3 - $featured->count();
        if ($newsLimit > 0 && $newsLimit <= 3) {
            $remainderArticles = Blog::with('thumbnail')->latest()->where('published', true)->limit($newsLimit)->get();
            $stickies = $stickies->concat($featured)->concat($remainderArticles);
        }

        // Mengambil semua kendaraan beserta merknya
        $vehicles = Vehicle::with('brand')->get();

        // Mengambil semua merk
        $brands = Brand::all();

        // Menggabungkan daftar kendaraan dan merk untuk ditampilkan
        $combinedList = $vehicles->map(function ($vehicle) { //fungsi ini menerapkan callback pada setiap elemen dari koleksi
            return [
                'name' => $vehicle->brand->name . ' ' . $vehicle->name,
                'type' => 'vehicle'
            ];
        })->concat($brands->map(function ($brand) {
            return [
                'name' => $brand->name,
                'type' => 'brand'
            ];
            //Menghasilkan satu koleksi baru yang berisi elemen-elemen dari kedua koleksi
            //di mana setiap elemen adalah array asosiatif yang menggambarkan baik kendaraan maupun merk.
        }));

        // Inisialisasi variabel kendaraan dan kategori spesifikasi
        $vehicle1 = null;
        $vehicle2 = null;
        $specCategories = collect();
        $errorMessage = null; // Tambahkan variabel untuk pesan kesalahan

        // Proses ketika form di-submit
        if ($request->isMethod('post')) {
            // Memisahkan input menjadi nama merk dan nama kendaraan
            $vehicle1Input = explode(' ', $request->vehicle1, 2);
            $vehicle2Input = explode(' ', $request->vehicle2, 2);

            // Periksa apakah input memiliki dua elemen
            if (count($vehicle1Input) < 2 || count($vehicle2Input) < 2) {
                $errorMessage = 'Data tidak ditemukan';
            } else {
                list($brand1Name, $vehicle1Name) = $vehicle1Input;
                list($brand2Name, $vehicle2Name) = $vehicle2Input;

                // Mengambil data merk berdasarkan nama merk
                $brand1 = Brand::where('name', $brand1Name)->first();
                $brand2 = Brand::where('name', $brand2Name)->first();

                // Mengambil data kendaraan berdasarkan nama kendaraan dan id merk
                $vehicle1 = $brand1 ? Vehicle::where('name', $vehicle1Name)->where('brand_id', $brand1->id)->first() : null;
                $vehicle2 = $brand2 ? Vehicle::where('name', $vehicle2Name)->where('brand_id', $brand2->id)->first() : null;

                // Jika salah satu kendaraan tidak ditemukan, set pesan kesalahan
                if (!$vehicle1 || !$vehicle2) {
                    $errorMessage = 'Data tidak ditemukan';
                } else {
                    // Mengambil kategori spesifikasi beserta spesifikasinya untuk kedua kendaraan
                    $specCategories = SpecCategory::with(['specs.vehicles' => function ($query) use ($vehicle1, $vehicle2) {
                        $query->whereIn('vehicles.id', [$vehicle1->id, $vehicle2->id]);
                    }])->get();
                }
            }
        }

        // Menampilkan view 'compare.index' dengan data yang dibutuhkan
        return view('compare.index', [
            'logo' => $logo,
            'bikeBrands' => $bikeBrands,
            'carBrands' => $carBrands,
            'posts' => Blog::with('thumbnail')
                ->latest()
                ->where('published', true)
                ->search($request->q)
                ->paginate(15),
            'banner' => is_null($banner) || is_null($banner->thumbnail) ? null : $banner->thumbnail,
            'recentVehicles' => Vehicle::with('brand')->latest()->limit(8)->get(),
            'popularVehicles' => Vehicle::with('brand')
                ->whereHas('views', fn (\Illuminate\Database\Eloquent\Builder $query) => $query->where('created_at', '>', now()->subMonths(3)))
                ->withCount('views')
                ->orderBy('views_count', 'desc')
                ->limit(10)
                ->get(),
            'stickies' => $stickies,
            'vehicles' => $vehicles,
            'vehicle1' => $vehicle1,
            'vehicle2' => $vehicle2,
            'specCategories' => $specCategories,
            'brands' => $brands,
            'combinedList' => $combinedList,
            'errorMessage' => $errorMessage, // Tambahkan pesan kesalahan ke view
        ]);
    }

    // Method untuk menampilkan detail kendaraan
    public function showVehicle($id)
    {
        // Mengambil data kendaraan berdasarkan ID
        $vehicle = Vehicle::findOrFail($id);

        // Menampilkan view 'vehicle.show' dengan data kendaraan
        return view('vehicle.show', compact('vehicle'));
    }
}

<?php

namespace App\Http\Controllers\backend;

use App\Models\Type;
use App\Models\Brand;
use App\Models\Blog;
use App\Models\StickyArticle;
use App\Models\TipsAndTrick;
use App\Models\Spec;
use App\Models\Vehicle;
use App\Models\Marketplace;
use App\Models\Comment;
use App\Http\Controllers\Controller;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class OptionController extends Controller
{
    public function index()
    {
        // Pastikan entri awal ada
        $requiredBanners = ['blog', 'brand', 'type'];
        foreach ($requiredBanners as $name) {
            if (!Option::where('type', 'banner')->where('name', $name)->exists()) {
                Option::create([
                    'type' => 'banner',
                    'name' => $name,
                    'value' => '' // Nilai default untuk kolom value
                ]);
            }
        }
        if (!Option::where('type', 'logo')->exists()) {
            Option::create([
                'type' => 'logo',
                'name' => 'site_logo',
                'value' => '' // Nilai default untuk kolom value
            ]);
        }

        return view('backend.option.index', [
            'banners' => Option::where('type', 'banner')->orderBy('name')->with('thumbnail')->get(),
            'logo' => Option::where('type', 'logo')->with('thumbnail')->first()
        ]);
    }

    public function update(Request $request)
    {
        // Validasi file yang diupload
        $request->validate([
            'banner.*' => 'nullable|image|max:2048',
            'logo' => 'nullable|image|max:2048',
        ]);

        // Log request data untuk debugging
        Log::info('Request data: ', $request->all());

        // Logic untuk banner
        if ($request->hasFile('banner')) {
            $newBanners = array_values($request->file('banner'));
            $ids = $request->banner_id ?? [];

            foreach ($newBanners as $index => $newBanner) {
                if (!isset($ids[$index])) {
                    Log::warning("Banner ID pada index {$index} tidak ditemukan. Melewati.");
                    continue;
                }

                $currentBanner = Option::with('thumbnail')->find($ids[$index]);

                // Jika banner tidak ditemukan, buat entri baru
                if (!$currentBanner) {
                    Log::warning("Banner ID {$ids[$index]} tidak ditemukan. Membuat entri baru.");
                    $currentBanner = Option::create([
                        'type' => 'banner',
                        'name' => 'banner_' . $ids[$index],
                        'value' => '' // Nilai default untuk kolom value
                    ]);
                }

                if ($currentBanner->thumbnail) {
                    Storage::delete('public/' . $currentBanner->thumbnail->path);
                    $currentBanner->thumbnail->delete();
                    Log::info("Gambar banner lama dihapus: " . $currentBanner->thumbnail->path);
                }

                $path = $newBanner->store('banner', 'public');
                $currentBanner->thumbnail()->create([
                    'path' => $path,
                    'fileable_id' => $currentBanner->id,
                    'fileable_type' => Option::class
                ]);
                Log::info("Banner ID {$ids[$index]} berhasil diperbarui dengan path: {$path}");
            }
        }

        // Logic untuk logo
        if ($request->hasFile('logo')) {
            Log::info("File logo diunggah: " . $request->file('logo')->getClientOriginalName());
            $newLogo = $request->file('logo');
            $currentLogo = Option::where('type', 'logo')->with('thumbnail')->first();

            // Jika logo tidak ditemukan, buat entri baru
            if (!$currentLogo) {
                Log::info("Logo dengan type 'logo' tidak ditemukan. Membuat entri baru.");
                $currentLogo = Option::create([
                    'type' => 'logo',
                    'name' => 'site_logo',
                    'value' => '' // Nilai default untuk kolom value
                ]);
            }

            if ($currentLogo->thumbnail) {
                Storage::delete('public/' . $currentLogo->thumbnail->path);
                $currentLogo->thumbnail->delete();
                Log::info("Gambar logo lama dihapus: " . $currentLogo->thumbnail->path);
            }

            $path = $newLogo->store('assets', 'public');
            $currentLogo->thumbnail()->create([
                'path' => $path,
                'fileable_id' => $currentLogo->id,
                'fileable_type' => Option::class
            ]);
            Log::info("Logo baru disimpan dengan path: {$path}");
        } else {
            Log::warning("Tidak ada file logo yang diunggah.");
        }

        return redirect()->route('backend.option.index')->with('success', 'Assets updated successfully.');
    }

    public function dashboard()
    {
        $totalTypes = Type::count();
        $totalBrands = Brand::count();
        $totalBlogs = Blog::count();
        $totalStickyArticles = StickyArticle::count();
        $totalSpecs = Spec::count();
        $totalVehicles = Vehicle::count();
        $totalMarketplaces = Marketplace::count();
        $totalComments = Comment::count();
        $totalTipsAndTrick = TipsAndTrick::count();

        return view('backend.option.dashboard', compact(
            'totalTypes', 'totalBrands', 'totalBlogs', 'totalStickyArticles',
            'totalSpecs', 'totalVehicles', 'totalMarketplaces', 'totalComments', 'totalTipsAndTrick'
        ));
    }
}
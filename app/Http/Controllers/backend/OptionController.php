<?php

namespace App\Http\Controllers\backend;

use App\Models\Type;
use App\Models\Brand;
use App\Models\Blog;
use App\Models\StickyArticle;
use App\Models\Spec;
use App\Models\Vehicle;
use App\Models\Marketplace;
use App\Models\Comment;
use App\Http\Controllers\Controller;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OptionController extends Controller
{
    public function index() {
        return view('backend.option.index', [
            'banners' => Option::where('type', 'banner')->orderBy('name')->with('thumbnail')->get(),
            'logo' => Option::where('type', 'logo')->with('thumbnail')->first()
        ]);
    }

    public function update(Request $request) {
        // Validasi file yang diupload
        $request->validate([
            'banner.*' => 'nullable|image|max:2048',  // Maksimal 2MB untuk banner
            'logo' => 'nullable|image|max:2048',      // Maksimal 2MB untuk logo
        ]);

        // Logic untuk banner
        if ($request->hasFile('banner')) {
            $newBanners = array_values($request->file('banner'));
            $ids = $request->banner_id;

            foreach ($newBanners as $index => $newBanner) {
                $currentBanner = Option::with('thumbnail')->find($ids[$index]);

                // Pastikan $currentBanner ditemukan
                if (!$currentBanner) {
                    continue; // Lanjutkan jika ID tidak ditemukan
                }

                if ($currentBanner->thumbnail) {
                    // Hapus gambar lama jika ada
                    Storage::delete('public/' . $currentBanner->thumbnail->path);
                    $currentBanner->thumbnail->delete();
                }

                // Simpan gambar baru
                $currentBanner->thumbnail()->create([
                    'path' => $newBanner->store('banner', 'public')
                ]);
            }
        }

        // Logic untuk logo
        if ($request->hasFile('logo')) {
            $newLogo = $request->file('logo');
            $currentLogo = Option::with('thumbnail')->find($request->logo_id);

            // Pastikan $currentLogo ditemukan
            if (!$currentLogo) {
                return redirect()->route('backend.option.index')->with('error', 'Logo ID tidak ditemukan.');
            }

            if ($currentLogo->thumbnail) {
                // Hapus gambar lama jika ada
                Storage::delete('public/' . $currentLogo->thumbnail->path);
                $currentLogo->thumbnail->delete();
            }

            // Simpan gambar logo baru
            $currentLogo->thumbnail()->create([
                'path' => $newLogo->store('assets', 'public')
            ]);
        }

        // Redirect setelah sukses
        return redirect()->route('backend.option.index')->with('success', 'Assets updated successfully.');
    }

    public function dashboard()
    {
        // Mengambil total jumlah data dari setiap model
        $totalTypes = Type::count();
        $totalBrands = Brand::count();
        $totalBlogs = Blog::count();
        $totalStickyArticles = StickyArticle::count();
        $totalSpecs = Spec::count();
        $totalVehicles = Vehicle::count();
        $totalMarketplaces = Marketplace::count();
        $totalComments = Comment::count();

        // Mengirimkan data ke view dashboard
        return view('backend.option.dashboard', compact(
            'totalTypes', 'totalBrands', 'totalBlogs', 'totalStickyArticles', 
            'totalSpecs', 'totalVehicles', 'totalMarketplaces', 'totalComments'
        ));
    }
}

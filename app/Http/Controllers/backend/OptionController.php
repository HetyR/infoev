<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Option;
use App\Models\Type;
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
        if ($request->hasFile('banner')) {
            $newBanners = array_values($request->file('banner'));
            $ids = $request->banner_id;

            for ($i=0; $i < count($newBanners); $i++) {
                $currentBanner = Option::with('thumbnail')->find($ids[$i]);
                if ($currentBanner->thumbnail) {
                    Storage::delete('public/' . $currentBanner->thumbnail->path);
                    $currentBanner->thumbnail->delete();
                }

                $currentBanner->thumbnail()->create([
                    'path' => $newBanners[$i]->store('banner', 'public')
                ]);
            }
        }

        if ($request->hasFile('logo')) {
            $newLogo = $request->file('logo');
            $currentLogo = Option::with('thumbnail')->find($request->logo_id);

            if ($currentLogo->thumbnail) {
                Storage::delete('public/' . $currentLogo->thumbnail->path);
                $currentLogo->thumbnail->delete();
            }

            $currentLogo->thumbnail()->create([
                'path' => $newLogo->store('assets', 'public')
            ]);
        }

        return redirect()->route('backend.option.index');
    }

    public function dashboard() {
        return view('backend.option.dashboard');
    }
}

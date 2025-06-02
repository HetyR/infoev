<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function index()
    {
        return view('backend.brand.index', [
            'brands' => Brand::orderBy('name')->get()
        ]);
    }

    public function create()
    {
        return view('backend.brand.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'banner' => ['nullable', 'image', 'max:2048'], 
        ]);

        $brand = Brand::create([
            'name' => $validated['name'],
        ]);

        if ($request->hasFile('banner')) {
            $brand->thumbnail()->create([
                'path' => $request->file('banner')->store('banner', 'public'),
            ]);
        }

        return redirect()->route('backend.brand.index')->with('success', 'Brand berhasil ditambahkan!');
    }


    public function edit(Brand $brand)
    {
        return view('backend.brand.edit', [
            'brand' => $brand,
            'banner' => $brand->thumbnail
        ]);
    }

    public function update(Request $request, Brand $brand)
    {
        $formFields = [
            'name' => $request->name
        ];

        $brand->update($formFields);

        if ($request->hasFile('banner')) {
            $banner = $brand->thumbnail;
            if (!is_null($banner)) {
                Storage::delete('public/' . $banner->path);
                $banner->delete();
            }

            $brand->thumbnail()->create([
                'path' => $request->file('banner')->store('banner', 'public')
            ]);
        }

        return redirect()->route('backend.brand.index');
    }

    public function destroy(Brand $brand)
    {
        $banner = $brand->thumbnail;
        if (!is_null($banner)) {
            Storage::delete('public/' . $banner->path);
            $banner->delete();
        }

        $brand->delete();
        return redirect()->route('backend.brand.index');
    }
}

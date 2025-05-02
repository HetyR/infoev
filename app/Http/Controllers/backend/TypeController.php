<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\isNull;

class TypeController extends Controller
{
    public function index() {
        return view('backend.type.index', [
            'types' => Type::orderBy('name')->get()
        ]);
    }

    public function create() {
        return view('backend.type.create');
    }

    public function store(Request $request) {
        $formFields = [
            'name' => $request->name
        ];

        $type = Type::create($formFields);

        if ($request->hasFile('banner')) {
            $type->thumbnail()->create([
                'path' => $request->file('banner')->store('banner', 'public')
            ]);
        }

        return redirect()->route('backend.type.index');
    }

    public function edit(Type $type) {
        return view('backend.type.edit', [
            'type' => $type,
            'banner' => $type->thumbnail
        ]);
    }

    public function update(Request $request, Type $type) {
        $formFields = [
            'name' => $request->name
        ];

        $type->update($formFields);

        if ($request->hasFile('banner')) {
            $banner = $type->thumbnail;
            if (!is_null($banner)) {
                Storage::delete('public/' . $banner->path);
                $banner->delete();
            }

            $type->thumbnail()->create([
                'path' => $request->file('banner')->store('banner', 'public')
            ]);
        }

        return redirect()->route('backend.type.index');
    }

    public function destroy(Type $type) {
        $banner = $type->thumbnail;
        if (!is_null($banner)) {
            Storage::delete('public/' . $banner->path);
            $banner->delete();
        }

        $type->delete();
        return redirect()->route('backend.type.index');
    }
}

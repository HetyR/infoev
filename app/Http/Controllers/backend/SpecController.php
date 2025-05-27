<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Spec;
use App\Models\SpecCategory;
use Illuminate\Http\Request;
use PhpParser\Node\NullableType;

class SpecController extends Controller
{
    public function index()
    {
        return view('backend.spec.index', [
            'specs' => SpecCategory::with('specs')->orderBy('priority', 'asc')->get()
        ]);
    }

    // Specification Category
    public function createCategory()
    {
        return view('backend.spec.category.create');
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'priority' => 'required|integer|min:1',
        ]);

        SpecCategory::create($validated);

        return redirect()->route('backend.spec.index');
    }


    public function editCategory(SpecCategory $spec)
    {
        return view('backend.spec.category.edit', [
            'cat' => $spec,
        ]);
    }

    public function updateCategory(Request $request, SpecCategory $spec)
    {
        $formFields = [
            'name' => $request->name,
            'priority' => $request->priority
        ];

        $spec->update($formFields);
        return redirect()->route('backend.spec.index');
    }

    public function destroyCategory(SpecCategory $spec)
    {
        $spec->delete();
        return redirect()->route('backend.spec.index');
    }

    // Specification
    public function createSpec()
    {
        return view('backend.spec.spec.create', [
            'categories' => SpecCategory::all()
        ]);
    }
    public function storeSpec(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:price,unit,description,text',
            'unit' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'catId' => 'required|exists:spec_categories,id',
            'hidden' => 'nullable|boolean',
        ]);

        $formFields = [
            'name' => $validated['name'],
            'type' => $validated['type'],
            'unit' => null,
            'description' => null,
            'hidden' => $request->has('hidden'), // checkbox handling
        ];

        switch ($validated['type']) {
            case 'price':
            case 'unit':
                $formFields['unit'] = $validated['unit'] ?? null;
                break;
            case 'description':
                $formFields['description'] = $validated['description'] ?? null;
                break;
        }

        SpecCategory::findOrFail($validated['catId'])
            ->specs()
            ->create($formFields);

        return redirect()->route('backend.spec.index');
    }



    public function editSpec(Spec $spec)
    {
        $spec->load('lists'); // penting! agar data list bisa dipanggil di view
        return view('backend.spec.spec.edit', [
            'spec' => $spec,
            'categories' => SpecCategory::all()
        ]);
    }

    public function updateSpec(Request $request, Spec $spec)
    {
        $cat = SpecCategory::find($request->catId);

        $formFields = [
            'name' => $request->name,
            'type' => $request->type,
            'unit' => null, // default null
            'description' => null,
        ];

        // Set value berdasarkan type
        switch ($request->type) {
            case 'price':
            case 'unit':
                $formFields['unit'] = $request->unit;
                break;
            case 'description':
                $formFields['description'] = $request->description;
                break;
                // list & availability tidak perlu tambahan disini
        }

        // Hidden checkbox
        $formFields['hidden'] = $request->has('hidden');

        // Simpan data
        $spec->fill($formFields);
        $spec->specCategory()->associate($cat);
        $spec->save();

        // Handle list jika type == list
        if ($request->type === 'list') {
            $spec->lists()->delete(); // bersihkan dulu
            if (is_array($request->specLists)) {
                foreach ($request->specLists as $list) {
                    if (!is_null($list) && trim($list) !== '') {
                        $spec->lists()->create(['list' => $list]);
                    }
                }
            }
        }

        return redirect()->route('backend.spec.index');
    }

    public function destroySpec(Spec $spec)
    {
        $spec->delete();
        return redirect()->route('backend.spec.index');
    }
}

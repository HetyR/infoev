<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Spec;
use App\Models\SpecCategory;
use Illuminate\Http\Request;
use PhpParser\Node\NullableType;

class SpecController extends Controller
{
    public function index() {
        return view('backend.spec.index', [
            'specs' => SpecCategory::with('specs')->orderBy('priority', 'asc')->get()
        ]);
    }

    // Specification Category
    public function createCategory() {
        return view('backend.spec.category.create');
    }

    public function storeCategory(Request $request) {
        $formFields = [
            'name' => $request->name,
            'priority' => $request->priority
        ];

        SpecCategory::create($formFields);
        return redirect()->route('backend.spec.index');
    }

    public function editCategory(SpecCategory $spec) {
        return view('backend.spec.category.edit', [
            'cat' => $spec,
        ]);
    }

    public function updateCategory(Request $request, SpecCategory $spec) {
        $formFields = [
            'name' => $request->name,
            'priority' => $request->priority
        ];

        $spec->update($formFields);
        return redirect()->route('backend.spec.index');
    }

    public function destroyCategory(SpecCategory $spec) {
        $spec->delete();
        return redirect()->route('backend.spec.index');
    }

    // Specification
    public function createSpec() {
        return view('backend.spec.spec.create', [
            'categories' => SpecCategory::all()
        ]);
    }

    public function storeSpec(Request $request) {
    $formFields = [
        'name' => $request->name,
        'type' => $request->type,
        'unit' => null,
        'description' => null,
    ];


        if (!is_null($request->hidden)) {
            $formFields['hidden'] = true;
        }

    switch ($request->type) {
        case 'price':
        case 'unit':
            $formFields['unit'] = $request->unit ?? null;
            break;
        case 'description':
            $formFields['description'] = $request->description ?? null; // boleh kosong
            break;
    }

        SpecCategory::find($request->catId)
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

    public function destroySpec(Spec $spec) {
        $spec->delete();
        return redirect()->route('backend.spec.index');
    }
}

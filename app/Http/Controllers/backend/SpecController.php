<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Spec;
use App\Models\SpecCategory;
use Illuminate\Http\Request;

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
            'name' => $request->name
        ];
        if (!is_null($request->hidden)) {
            $formFields['hidden'] = true;
        }

        SpecCategory::find($request->catId)
                    ->specs()
                    ->create($formFields);
        return redirect()->route('backend.spec.index');
    }

    public function editSpec(Spec $spec) {
        return view('backend.spec.spec.edit', [
            'spec' => $spec,
            'categories' => SpecCategory::all()
        ]);
    }

    public function updateSpec(Request $request, Spec $spec) {
        $cat = SpecCategory::find($request->catId);
        $formFields = [
            'name' => $request->name,
            'type' => $request->type,
            'unit' => is_null($request->unit) ? null : $request->unit,
        ];

        $spec->specCategory()->associate($cat);
        $spec->update($formFields);

        if (count($request->specLists) > 1 || !is_null($request->specLists[0])) {
            $spec->lists()->delete();
            foreach ($request->specLists as $list) {
                if (!is_null($list)) {
                    $spec->lists()->create(['list' => $list]);
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

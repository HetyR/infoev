<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Spec;
use App\Models\SpecCategory;
use App\Models\SpecList;

class SpecTestController extends Controller
{
    public function getSpecCategories()
    {
        $categories = SpecCategory::with('specs')->get();

        // Sembunyikan timestamps dari main model dan relasinya
        $categories->each(function ($category) {
            $category->makeHidden(['created_at', 'updated_at']);
            $category->specs->each->makeHidden(['created_at', 'updated_at']);
        });

        return response()->json([
            'success' => true,
            'message' => 'List of Spec Categories',
            'data' => $categories
        ]);
    }

    public function getSpecs()
    {
        $specs = Spec::with(['specCategory', 'vehicles', 'lists'])->get();

        $specs->each(function ($spec) {
            $spec->makeHidden(['created_at', 'updated_at']);

            if ($spec->specCategory) {
                $spec->specCategory->makeHidden(['created_at', 'updated_at']);
            }

            $spec->vehicles->each->makeHidden(['created_at', 'updated_at']);
            $spec->lists->each->makeHidden(['created_at', 'updated_at']);
        });

        return response()->json([
            'success' => true,
            'message' => 'List of Specs',
            'data' => $specs
        ]);
    }

    public function getSpecLists()
    {
        $lists = SpecList::with(['spec', 'specVehicles'])->get();

        $lists->each(function ($list) {
            $list->makeHidden(['created_at', 'updated_at']);

            if ($list->spec) {
                $list->spec->makeHidden(['created_at', 'updated_at']);
            }

            $list->specVehicles->each->makeHidden(['created_at', 'updated_at']);
        });

        return response()->json([
            'success' => true,
            'message' => 'List of Spec Lists',
            'data' => $lists
        ]);
    }
}

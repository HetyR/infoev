<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Marketplace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarketplaceController extends Controller
{
    public function index() {
        return view('backend.marketplace.index', [
            'marketplaces' => Marketplace::orderBy('name')->with('logo')->get()
        ]);
    }

    public function create() {
        return view('backend.marketplace.create');
    }

    public function store(Request $request) {
        $formFields = [
            'name' => $request->name
        ];

        $marketplace = Marketplace::create($formFields);

        if ($request->hasFile('logo')) {
            $marketplace->logo()->create([
                'path' => $request->file('logo')->store('assets', 'public')
            ]);
        }

        return redirect()->route('backend.marketplace.index');
    }

    public function edit(Marketplace $marketplace) {
        return view('backend.marketplace.edit', [
            'marketplace' => $marketplace,
            'logo' => $marketplace->logo
        ]);
    }

    public function update(Request $request, Marketplace $marketplace) {
        $formFields = [
            'name' => $request->name
        ];

        $marketplace->update($formFields);

        if ($request->hasFile('logo')) {
            $logo = $marketplace->logo;
            if (!is_null($logo)) {
                Storage::delete('public/' . $logo->path);
                $logo->delete();
            }

            $marketplace->logo()->create([
                'path' => $request->file('logo')->store('assets', 'public')
            ]);
        }

        return redirect()->route('backend.marketplace.index');
    }

    public function destroy(Marketplace $marketplace) {
        $logo = $marketplace->logo;
        if (!is_null($logo)) {
            Storage::delete('public/' . $logo->path);
            $logo->delete();
        }

        $marketplace->delete();
        return redirect()->route('backend.marketplace.index');
    }
}

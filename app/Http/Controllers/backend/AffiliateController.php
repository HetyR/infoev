<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\AffiliateLink;
use App\Models\Marketplace;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class AffiliateController extends Controller
{
    public function show(Vehicle $vehicle) {
        return view('backend.affiliate.show', [
            'vehicle' => $vehicle
        ]);
    }

    public function create(Vehicle $vehicle) {
        return view('backend.affiliate.create', [
            'vehicle' => $vehicle,
            'marketplaces' => Marketplace::orderBy('name')->get()
        ]);
    }

    public function store(Request $request, Vehicle $vehicle) {
        $marketplace = Marketplace::find($request->marketplace);

        $affiliate = new AffiliateLink;
        $affiliate->desc = $request->desc;
        $affiliate->price = $request->price;
        $affiliate->link = $request->link;

        $affiliate->vehicle()->associate($vehicle);
        $affiliate->marketplace()->associate($marketplace);
        $affiliate->save();

        return redirect()->route('backend.affiliate.show', ['vehicle' => $vehicle->slug]);
    }

    public function edit(AffiliateLink $affiliate, Vehicle $vehicle) {
        return view('backend.affiliate.edit', [
            'vehicle' => $vehicle,
            'marketplaces' => Marketplace::orderBy('name')->get(),
            'affiliate' => $affiliate
        ]);
    }

    public function update(Request $request, AffiliateLink $affiliate) {
        $marketplace = Marketplace::find($request->marketplace);
        $formFields = [
            'desc' => $request->desc,
            'price' => $request->price,
            'link' => $request->link
        ];

        $affiliate->marketplace()->associate($marketplace);
        $affiliate->update($formFields);

        return redirect()->route('backend.affiliate.show', ['vehicle' => $affiliate->vehicle->slug]);
    }

    public function destroy(AffiliateLink $affiliate) {
        $vehicle = $affiliate->vehicle;
        $affiliate->delete();
        return redirect()->route('backend.affiliate.show', ['vehicle' => $vehicle->slug]);
    }
}

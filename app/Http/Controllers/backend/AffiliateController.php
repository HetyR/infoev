<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\AffiliateLink;
use App\Models\Marketplace;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class AffiliateController extends Controller
{
    // public function show(Vehicle $vehicle) {
    //     return view('backend.affiliate.show', [
    //         'vehicle' => $vehicle
    //     ]);
    // }
    public function show(Vehicle $vehicle)
    {
        $affiliateLinks = AffiliateLink::with('marketplace')
            ->where('vehicle_id', $vehicle->id)
            ->get();

        return view('backend.affiliate.show', [
            'vehicle' => $vehicle,
            'affiliates' => $affiliateLinks,
        ]);
    }


    // public function show(Vehicle $vehicle)
    // {
    //     $affiliateLinks = AffiliateLink::with('marketplace')
    //         ->where('vehicle_id', $vehicle->id)
    //         ->get();

    //     return response()->json([
    //         'vehicle' => [
    //             'id' => $vehicle->id,
    //             'slug' => $vehicle->slug,
    //             'name' => $vehicle->name,
    //         ],
    //         'affiliates' => $affiliateLinks->map(function ($link) {
    //             return [
    //                 'id' => $link->id,
    //                 'desc' => $link->desc,
    //                 'price' => $link->price,
    //                 'link' => $link->link,
    //                 'marketplace' => [
    //                     'id' => $link->marketplace->id,
    //                     'name' => $link->marketplace->name,
    //                 ]
    //             ];
    //         })
    //     ]);
    // }

    public function create(Vehicle $vehicle)
    {
        return view('backend.affiliate.create', [
            'vehicle' => $vehicle,
            'marketplaces' => Marketplace::orderBy('name')->get()
        ]);
    }

    public function store(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'desc' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'link' => 'required|url',
            'marketplace' => 'required|exists:marketplaces,id',
        ]);

        $marketplace = Marketplace::find($validated['marketplace']);

        $affiliate = new AffiliateLink;
        $affiliate->desc = $validated['desc'];
        $affiliate->price = $validated['price'];
        $affiliate->link = $validated['link'];

        $affiliate->vehicle()->associate($vehicle);
        $affiliate->marketplace()->associate($marketplace);
        $affiliate->save();

        return redirect()->route('backend.affiliate.show', ['vehicle' => $vehicle->slug])
            ->with('success', 'Affiliate link berhasil ditambahkan.');
    }

    public function edit(AffiliateLink $affiliate, Vehicle $vehicle)
    {
        return view('backend.affiliate.edit', [
            'vehicle' => $vehicle,
            'marketplaces' => Marketplace::orderBy('name')->get(),
            'affiliate' => $affiliate
        ]);
    }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'vehicle_id' => 'required|exists:vehicles,id',
    //         'marketplace_id' => 'required|exists:marketplaces,id',
    //         'desc' => 'nullable|string',
    //         'price' => 'required|numeric|min:0',
    //         'link' => 'required|url'
    //     ]);

    //     $affiliate = new AffiliateLink();
    //     $affiliate->desc = $validated['desc'];
    //     $affiliate->price = $validated['price'];
    //     $affiliate->link = $validated['link'];
    //     $affiliate->vehicle_id = $validated['vehicle_id'];
    //     $affiliate->marketplace_id = $validated['marketplace_id'];
    //     $affiliate->save();

    //     return response()->json([
    //         'message' => 'Affiliate link created successfully',
    //         'data' => $affiliate
    //     ], 201);
    // }

    public function update(Request $request, AffiliateLink $affiliate)
    {
        $validated = $request->validate([
            'desc' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'link' => 'required|url',
            'marketplace' => 'required|exists:marketplaces,id',
        ]);

        $marketplace = Marketplace::find($validated['marketplace']);

        $affiliate->marketplace()->associate($marketplace);
        $affiliate->update([
            'desc' => $validated['desc'],
            'price' => $validated['price'],
            'link' => $validated['link'],
        ]);

        return redirect()->route('backend.affiliate.show', ['vehicle' => $affiliate->vehicle->slug])
            ->with('success', 'Affiliate link berhasil diperbarui.');
    }


    public function destroy(AffiliateLink $affiliate)
    {
        $vehicle = $affiliate->vehicle;
        $affiliate->delete();
        return redirect()->route('backend.affiliate.show', ['vehicle' => $vehicle->slug]);
    }
}

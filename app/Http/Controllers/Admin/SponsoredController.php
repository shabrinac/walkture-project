<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Spot;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;

class SponsoredController extends Controller
{
    public function __construct(private CloudinaryService $storage) {}

    public function index()
    {
        $spots = Spot::orderByDesc('is_sponsored')
                     ->orderBy('name')
                     ->get();

        $sponsoredCount = $spots->where('is_sponsored', true)->count();

        return view('admin.sponsored-places', compact('spots', 'sponsoredCount'));
    }

    /** Show the Add Sponsored Spot form */
    public function create()
    {
        return view('admin.sponsored-places.create');
    }

    /** Show full detail for a single spot */
    public function show($id)
    {
        $spot = Spot::findOrFail($id);
        return view('admin.spots.show', compact('spot'));
    }

    /** Persist a new sponsored spot */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'category'      => 'required|string|max:255',
            'latitude'      => 'required|numeric|between:-90,90',
            'longitude'     => 'required|numeric|between:-180,180',
            'description'   => 'nullable|string',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'image_url'     => 'nullable|url|max:2048',
            'promo_detail'  => 'nullable|string',
            'zone_polygon'  => 'nullable|string',
            'nearest_route' => 'nullable|string',
        ]);

        $validated['is_sponsored'] = true;

        // Upload image to Cloudinary for auto-optimization
        if ($request->hasFile('image')) {
            $validated['image_url'] = $this->storage->store(
                $request->file('image'),
                'spots'
            );
            unset($validated['image']);
        }

        Spot::create($validated);

        return redirect()->route('admin.sponsored-places')
                         ->with('success', 'Sponsored spot "' . $validated['name'] . '" added successfully.');
    }

    /** Update an existing spot */
    public function update(Request $request, $id)
    {
        $spot = Spot::findOrFail($id);

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'category'     => 'required|string|max:255',
            'latitude'     => 'required|numeric|between:-90,90',
            'longitude'    => 'required|numeric|between:-180,180',
            'description'  => 'nullable|string',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'image_url'    => 'nullable|url|max:2048',
            'promo_detail' => 'nullable|string',
        ]);

        // Upload new image to Cloudinary → delete old one from Cloudinary
        if ($request->hasFile('image')) {
            if ($spot->image_url && str_contains($spot->image_url, 'cloudinary.com')) {
                $this->storage->delete($spot->image_url);
            }

            $validated['image_url'] = $this->storage->store(
                $request->file('image'),
                'spots'
            );
            unset($validated['image']);
        }

        $spot->update($validated);

        return redirect()->route('admin.sponsored-places')
                         ->with('success', 'Spot "' . $spot->name . '" updated successfully.');
    }

    /** Delete a spot (also removes its image from Cloudinary) */
    public function destroy($id)
    {
        $spot = Spot::findOrFail($id);
        $name = $spot->name;

        // Clean up image from Cloudinary
        if ($spot->image_url && str_contains($spot->image_url, 'cloudinary.com')) {
            $this->storage->delete($spot->image_url);
        }

        $spot->delete();

        return redirect()->route('admin.sponsored-places')
                         ->with('success', 'Spot "' . $name . '" deleted successfully.');
    }
}

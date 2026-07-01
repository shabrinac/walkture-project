<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Spot;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;

class SpatialDataController extends Controller
{
    public function __construct(private CloudinaryService $storage) {}

    public function index()
    {
        $spots = Spot::orderBy('category')->orderBy('name')->get();
        return view('admin.spatial-data', compact('spots'));
    }

    /** Show full detail for a single spot */
    public function show($id)
    {
        $spot = Spot::findOrFail($id);
        return view('admin.spots.show', compact('spot'));
    }

    /** Show the Add Spot form */
    public function createSpot()
    {
        return view('admin.spatial-data.create-spot');
    }

    /** Persist a new Spot */
    public function storeSpot(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'category'        => 'required|string|max:255',
            'latitude'        => 'required|numeric|between:-90,90',
            'longitude'       => 'required|numeric|between:-180,180',
            'description'     => 'nullable|string',
            'image'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'is_sponsored'    => 'boolean',
            'promo_detail'    => 'nullable|string',
            'polygon_geojson' => 'nullable|string',
            'route_geojson'   => 'nullable|string',
        ]);

        $validated['is_sponsored'] = $request->boolean('is_sponsored');

        // Upload image to Cloudinary for auto-optimization
        if ($request->hasFile('image')) {
            $validated['image_url'] = $this->storage->store(
                $request->file('image'),
                'spots'
            );
            $validated['image_path'] = null; // Clear local storage paths
        }
        unset($validated['image']);

        // Parse GeoJSON fields
        foreach (['polygon_geojson', 'route_geojson'] as $field) {
            if (!empty($validated[$field])) {
                $decoded = json_decode($validated[$field], true);
                $validated[$field] = (json_last_error() === JSON_ERROR_NONE) ? $decoded : null;
            }
        }

        Spot::create($validated);

        return redirect()->route('admin.spatial-data')
                         ->with('success', 'Spot "' . $validated['name'] . '" added successfully.');
    }

    /** Show the Edit Spot form */
    public function editSpot($id)
    {
        $spot = Spot::findOrFail($id);
        return redirect()->route('admin.spatial-data')->with('edit_spot_id', $id);
    }

    /** Update an existing Spot */
    public function updateSpot(Request $request, $id)
    {
        $spot = Spot::findOrFail($id);

        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'category'        => 'required|string|max:255',
            'latitude'        => 'required|numeric|between:-90,90',
            'longitude'       => 'required|numeric|between:-180,180',
            'description'     => 'nullable|string',
            'image'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'is_sponsored'    => 'boolean',
            'promo_detail'    => 'nullable|string',
            'polygon_geojson' => 'nullable|string',
            'route_geojson'   => 'nullable|string',
        ]);

        $validated['is_sponsored'] = $request->boolean('is_sponsored');

        // Upload new image to Cloudinary → delete old one from Cloudinary
        if ($request->hasFile('image')) {
            if ($spot->image_url && str_contains($spot->image_url, 'cloudinary.com')) {
                $this->storage->delete($spot->image_url);
            }

            $validated['image_url']  = $this->storage->store(
                $request->file('image'),
                'spots'
            );
            $validated['image_path'] = null; // Clear local path
        }
        unset($validated['image']);

        // Parse GeoJSON fields
        foreach (['polygon_geojson', 'route_geojson'] as $field) {
            if (!empty($validated[$field])) {
                $decoded = json_decode($validated[$field], true);
                $validated[$field] = (json_last_error() === JSON_ERROR_NONE) ? $decoded : null;
            }
        }

        $spot->update($validated);

        return redirect()->route('admin.spatial-data')
                         ->with('success', 'Spot "' . $validated['name'] . '" updated successfully.');
    }

    /** Delete a Spot (also removes image from Cloudinary) */
    public function destroySpot($id)
    {
        $spot = Spot::findOrFail($id);
        $name = $spot->name;

        // Delete image from Cloudinary
        if ($spot->image_url && str_contains($spot->image_url, 'cloudinary.com')) {
            $this->storage->delete($spot->image_url);
        }

        $spot->delete();

        return redirect()->route('admin.spatial-data')
                         ->with('success', 'Spot "' . $name . '" deleted successfully.');
    }
}

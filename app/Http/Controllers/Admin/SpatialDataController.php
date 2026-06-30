<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Spot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SpatialDataController extends Controller
{
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
            'image'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'is_sponsored'    => 'boolean',
            'promo_detail'    => 'nullable|string',
            'polygon_geojson' => 'nullable|string',
            'route_geojson'   => 'nullable|string',
        ]);

        $validated['is_sponsored'] = $request->boolean('is_sponsored');

        // Handle image file upload
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('spots', 'public');
        }
        unset($validated['image']); // remove from validated so Spot::create doesn't choke

        // Validate GeoJSON fields are parseable and assign as array for Eloquent casting
        foreach (['polygon_geojson', 'route_geojson'] as $field) {
            if (!empty($validated[$field])) {
                $decoded = json_decode($validated[$field], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $validated[$field] = $decoded;
                } else {
                    $validated[$field] = null;
                }
            }
        }

        Spot::create($validated);

        return redirect()->route('admin.spatial-data')
                         ->with('success', 'Spot "' . $validated['name'] . '" added successfully.');
    }

    /** Show the Edit Spot form (kept for direct-URL access; index uses modal) */
    public function editSpot($id)
    {
        $spot = Spot::findOrFail($id);
        // Return the spot data as JSON for the modal (AJAX) or fallback to index
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
            'image'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'is_sponsored'    => 'boolean',
            'promo_detail'    => 'nullable|string',
            'polygon_geojson' => 'nullable|string',
            'route_geojson'   => 'nullable|string',
        ]);

        $validated['is_sponsored'] = $request->boolean('is_sponsored');

        // Handle image file upload — replace old file if new one provided
        if ($request->hasFile('image')) {
            // Delete old uploaded file (not external URLs)
            if ($spot->image_path) {
                Storage::disk('public')->delete($spot->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('spots', 'public');
        }
        unset($validated['image']);

        // Validate GeoJSON fields are parseable and assign as array for Eloquent casting
        foreach (['polygon_geojson', 'route_geojson'] as $field) {
            if (!empty($validated[$field])) {
                $decoded = json_decode($validated[$field], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $validated[$field] = $decoded;
                } else {
                    $validated[$field] = null;
                }
            }
        }

        $spot->update($validated);

        return redirect()->route('admin.spatial-data')
                         ->with('success', 'Spot "' . $validated['name'] . '" updated successfully.');
    }

    /** Delete a Spot */
    public function destroySpot($id)
    {
        $spot = Spot::findOrFail($id);
        $name = $spot->name;

        // Delete uploaded image file if any
        if ($spot->image_path) {
            Storage::disk('public')->delete($spot->image_path);
        }

        $spot->delete();

        return redirect()->route('admin.spatial-data')
                         ->with('success', 'Spot "' . $name . '" deleted successfully.');
    }
}

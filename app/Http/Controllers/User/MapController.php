<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Spot;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index(Request $request)
    {
        $search   = $request->get('search', '');
        $category = $request->get('category', '');

        $query = Spot::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('description', 'ilike', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        $spots = $query->get([
            'id', 'name', 'category', 'latitude', 'longitude',
            'description', 'image_url', 'image_path', 'is_sponsored',
            'polygon_geojson', 'route_geojson',
        ]);

        // Get distinct categories for the filter dropdown
        $categories = Spot::distinct()->orderBy('category')->pluck('category');

        return view('user.map', compact('spots', 'categories', 'search', 'category'));
    }
}

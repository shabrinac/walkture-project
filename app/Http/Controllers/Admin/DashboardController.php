<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Spot;
use App\Models\PhotographerDirectory;
use App\Models\User;
use App\Models\ContactMessage;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_spots'         => Spot::count(),
            'sponsored_spots'     => Spot::where('is_sponsored', true)->count(),
            'spatial_areas'       => Spot::whereNotNull('zone_polygon')->orWhereNotNull('nearest_route')->count(),
            'photographers'       => PhotographerDirectory::where('is_active', true)->count(),
            'total_users'         => User::where('role', 'user')->count(),
            'unread_messages'     => ContactMessage::where('is_read', false)->count(),
        ];

        $recentSpots = Spot::latest()->take(5)->get();
        $recentPhotographers = PhotographerDirectory::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentSpots', 'recentPhotographers'));
    }
}


<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Spot;
use App\Models\PhotographerDirectory;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch sponsored spots for the "Featured Places" section
        $sponsoredSpots = Spot::sponsored()
            ->latest()
            ->take(6)
            ->get();

        // Fetch featured/active photographers for the dashboard showcase
        $featuredPhotographers = PhotographerDirectory::featured()
            ->latest()
            ->take(4)
            ->get();

        return view('user.dashboard', compact('sponsoredSpots', 'featuredPhotographers'));
    }
}

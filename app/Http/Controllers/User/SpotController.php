<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Spot;

class SpotController extends Controller
{
    public function show($id)
    {
        $spot = Spot::findOrFail($id);
        return view('user.spots.show', compact('spot'));
    }
}

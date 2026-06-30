<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PhotographerDirectory;

class PhotographersController extends Controller
{
    public function index()
    {
        // All active photographers for the directory page
        $photographers = PhotographerDirectory::active()
            ->orderByDesc('paid_until')   // Featured (paid) photographers first
            ->orderBy('full_name')
            ->get();

        $specialties = $photographers->pluck('specialty')->unique()->sort()->values();

        return view('user.photographers', compact('photographers', 'specialties'));
    }

    public function show($id)
    {
        $photographer = PhotographerDirectory::findOrFail($id);
        return view('user.photographers.show', compact('photographer'));
    }
}

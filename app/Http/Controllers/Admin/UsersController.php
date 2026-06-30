<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UsersController extends Controller
{
    public function index()
    {
        // All registered users with basic info, paginated for large datasets
        $users = User::orderBy('role')
                     ->orderBy('name')
                     ->paginate(20);

        $totalUsers  = User::where('role', 'user')->count();
        $totalAdmins = User::where('role', 'admin')->count();

        return view('admin.users', compact('users', 'totalUsers', 'totalAdmins'));
    }
}

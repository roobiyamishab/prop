<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function users()
    {
        // Example: list all users for admin
        $users = \App\Models\User::latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }
}


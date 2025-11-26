<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AdminUserController extends Controller
{
    // Show user details + edit form
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    // Handle basic profile update from admin
    public function updateBasicProfile(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|max:20',
            'location' => 'required|string|max:255',
        ]);

        $user->update($data);

        return back()->with('success', 'User profile updated successfully!');
    }
}

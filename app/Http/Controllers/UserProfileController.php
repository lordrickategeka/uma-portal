<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    public function index()
    {
        $profile = Auth::user()->profile;
        return view('profile.index', compact('profile'));
    }

    public function edit()
    {
        $profile = Auth::user()->profile;
        return view('profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'uma_branch' => 'nullable|string',
            'employer' => 'nullable|string',
        ]);

        Auth::user()->profile->update($request->all());

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully');
    }
}

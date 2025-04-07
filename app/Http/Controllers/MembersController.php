<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class MembersController extends Controller
{
    public function index()
    {
        $users = User::with('profile')->get();
        return view('dashboard.members.all_members', compact('users'));
    }
}

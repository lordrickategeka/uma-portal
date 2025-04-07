<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchesController extends Controller
{
    public function index() {
        $branches = Branch::all();
        return view('dashboard.branches.all_branch', compact('branches'));
    }
    
    public function create() {
        return view('dashboard.branches.create');
    }
    
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'manager_name' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);
    
        Branch::create($validated);
        return redirect()->route('branches.index')->with('success', 'Branch created successfully!');
    }
    
    public function show(Branch $branch) {
        return view('dasboard.branches.show', compact('branch'));
    }
    
    public function edit(Branch $branch) {
        return view('dashboard.branches.edit', compact('branch'));
    }
    
    public function update(Request $request, Branch $branch) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'manager_name' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);
    
        $branch->update($validated);
        return redirect()->route('branches.index')->with('success', 'Branch updated!');
    }
    
    public function destroy(Branch $branch) {
        $branch->delete();
        return redirect()->route('branches.index')->with('success', 'Branch deleted!');
    }
    
}

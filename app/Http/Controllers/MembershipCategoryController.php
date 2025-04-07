<?php

namespace App\Http\Controllers;

use App\Models\MembershipCategory;
use Illuminate\Http\Request;

class MembershipCategoryController extends Controller
{
    public function index()
    {
        $categories = MembershipCategory::withTrashed()->latest()->paginate(10);
        return view('dashboard.membership_categories.all_membership_categories', compact('categories'));
    }

    public function create()
    {
        return view('dashboard.membership_categories.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:membership_categories,name|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        MembershipCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('membership-categories.index')->with('success', 'Category created successfully.');
    }

    public function show(MembershipCategory $membershipCategory)
    {
        return view('dashboard.membership_categories.show', compact('membershipCategory'));
    }

    public function edit(MembershipCategory $membershipCategory)
    {
        return view('dashboard.membership_categories.edit', compact('membershipCategory'));
    }

    public function update(Request $request, MembershipCategory $membershipCategory)
    {
        $request->validate([
            'name' => 'required|unique:membership_categories,name,' . $membershipCategory->id . '|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $membershipCategory->update($request->all());

        return redirect()->route('membership-categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(MembershipCategory $membershipCategory)
    {
        // Soft delete all plans associated with this membership category
        $membershipCategory->plans()->delete();

        // Soft delete the category itself
        $membershipCategory->delete();

        // Redirect with success message
        return redirect()->route('membership-categories.index')->with('success', 'Category deleted successfully.');
    }

    public function showDeleted()
    {
        // Retrieve only soft-deleted categories
        $deletedCategories = MembershipCategory::onlyTrashed()->latest()->paginate(10);
        return view('dashboard.membership_categories.deleted', compact('deletedCategories'));
    }

    public function restore($id)
    {
        $category = MembershipCategory::withTrashed()->find($id);

        if ($category) {
            $category->restore();
            return redirect()->route('membership-categories.index')->with('success', 'Category restored successfully.');
        }

        return redirect()->route('membership-categories.index')->with('error', 'Category not found.');
    }

    public function forcedDelete(MembershipCategory $membershipCategory)
    {
        // Soft delete the associated plans
        $membershipCategory->plans()->forceDelete();  // Use delete to soft delete plans

        // Now delete the category itself permanently
        $membershipCategory->forceDelete();

        return redirect()->route('membership-categories.index')->with('success', 'Category and its associated plans deleted permanently.');
    }
}

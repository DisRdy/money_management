<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get all categories for this tenant
        $incomeCategories = Category::where('tenant_id', $user->tenant_id)
            ->where('type', 'income')
            ->with('user')
            ->latest()
            ->get();

        $expenseCategories = Category::where('tenant_id', $user->tenant_id)
            ->where('type', 'expense')
            ->with('user')
            ->latest()
            ->get();

        return view('categories.index', compact('incomeCategories', 'expenseCategories'));
    }

    public function create()
    {
        if (!Auth::user()->isOwner()) {
            abort(403, 'Only the family owner can create categories.');
        }

        return view('categories.create');
    }
    public function store(Request $request)
    {
        // Only owner can create categories
        if (!Auth::user()->isOwner()) {
            abort(403, 'Only the family owner can create categories.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'description' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();

        // Create category with relationships
        $category = Category::create([
            'tenant_id' => $user->tenant_id,
            'user_id' => $user->id, // Link to creator (demonstrates User → Category relationship)
            'name' => $validated['name'],
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully!');
    }
    public function show(Category $category)
    {
        // Ensure category belongs to user's tenant
        if ($category->tenant_id !== Auth::user()->tenant_id) {
            abort(403, 'Unauthorized access.');
        }

        // Load relationships for display
        $category->load(['user', 'transactions.user']);

        return view('categories.show', compact('category'));
    }
    public function edit(Category $category)
    {
        // Only owner can edit categories
        if (!Auth::user()->isOwner()) {
            abort(403, 'Only the family owner can edit categories.');
        }

        // Ensure category belongs to user's tenant
        if ($category->tenant_id !== Auth::user()->tenant_id) {
            abort(403, 'Unauthorized access.');
        }

        return view('categories.edit', compact('category'));
    }
    public function update(Request $request, Category $category)
    {
        // Only owner can update categories
        if (!Auth::user()->isOwner()) {
            abort(403, 'Only the family owner can update categories.');
        }

        // Ensure category belongs to user's tenant
        if ($category->tenant_id !== Auth::user()->tenant_id) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'description' => 'nullable|string|max:1000',
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        if (!Auth::user()->isOwner()) {
            abort(403, 'Only the family owner can delete categories.');
        }

        if ($category->tenant_id !== Auth::user()->tenant_id) {
            abort(403, 'Unauthorized access.');
        }

        if ($category->transactions()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Cannot delete category with existing transactions. Please reassign or delete the transactions first.');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully!');
    }
}

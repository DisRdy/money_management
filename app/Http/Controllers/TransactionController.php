<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Transaction::visibleTo($user)
            ->with(['user', 'category']); 

        if ($request->has('type') && in_array($request->type, ['income', 'expense'])) {
            $query->where('type', $request->type);
        }

        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('start_date') && $request->start_date) {
            $query->where('transaction_date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->where('transaction_date', '<=', $request->end_date);
        }

        $transactions = $query->latest('transaction_date')->paginate(15);

        $categories = Category::where('tenant_id', $user->tenant_id)
            ->orderBy('name')
            ->get();

        return view('transactions.index', compact('transactions', 'categories'));
    }
    public function create()
    {
        $user = Auth::user();

        // Get categories for this tenant
        $categories = Category::where('tenant_id', $user->tenant_id)
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        return view('transactions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'description' => 'required|string|max:1000',
            'notes' => 'nullable|string|max:2000',
        ]);

        $user = Auth::user();

        // Verify category belongs to same tenant
        $category = Category::findOrFail($validated['category_id']);
        if ($category->tenant_id !== $user->tenant_id) {
            abort(403, 'Invalid category selected.');
        }

        // Verify category type matches transaction type
        if ($category->type !== $validated['type']) {
            return back()->withErrors([
                'category_id' => 'Category type must match transaction type.'
            ])->withInput();
        }

        // Create transaction with relationships
        $transaction = Transaction::create([
            'tenant_id' => $user->tenant_id,
            'user_id' => $user->id, // Link to creator (demonstrates User → Transaction relationship)
            'category_id' => $validated['category_id'], // Link to category (demonstrates Category → Transaction relationship)
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'transaction_date' => $validated['transaction_date'],
            'description' => $validated['description'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction created successfully!');
    }

    public function show(Transaction $transaction)
    {
        $user = Auth::user();

        if ($transaction->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access.');
        }

        // Owner and Wife can view all tenant transactions
        if ($user->isChild() && $transaction->user_id !== $user->id) {
            abort(403, 'You can only view your own transactions.');
        }

        // Load relationships for display
        $transaction->load(['user', 'category']);

        return view('transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        $user = Auth::user();

        // Ensure transaction belongs to user's tenant
        if ($transaction->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access.');
        }

        // Authorization: Wife cannot edit Owner transactions
        if ($user->isWife()) {
            $transaction->load('user'); // Ensure user relationship is loaded
            if ($transaction->user->role === 'owner') {
                abort(403, 'You cannot modify Owner transactions.');
            }
            // Wife can only edit own or children's transactions
            if ($transaction->user_id !== $user->id && $transaction->user->role !== 'child') {
                abort(403, 'You can only edit your own and children transactions.');
            }
        }

        // Owner can edit all, Child can only edit their own
        if (!$user->isOwner() && !$user->isWife() && $transaction->user_id !== $user->id) {
            abort(403, 'You can only edit your own transactions.');
        }

        // Get categories for this tenant
        $categories = Category::where('tenant_id', $user->tenant_id)
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        return view('transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $user = Auth::user();

        // Ensure transaction belongs to user's tenant
        if ($transaction->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access.');
        }

        // Authorization: Wife cannot update Owner transactions
        if ($user->isWife()) {
            $transaction->load('user'); // Ensure user relationship is loaded
            if ($transaction->user->role === 'owner') {
                abort(403, 'You cannot modify Owner transactions.');
            }
            // Wife can only update own or children's transactions
            if ($transaction->user_id !== $user->id && $transaction->user->role !== 'child') {
                abort(403, 'You can only edit your own and children transactions.');
            }
        }

        // Owner can update all, Child can only update their own
        if (!$user->isOwner() && !$user->isWife() && $transaction->user_id !== $user->id) {
            abort(403, 'You can only edit your own transactions.');
        }

        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'description' => 'required|string|max:1000',
            'notes' => 'nullable|string|max:2000',
        ]);

        // Verify category belongs to same tenant
        $category = Category::findOrFail($validated['category_id']);
        if ($category->tenant_id !== $user->tenant_id) {
            abort(403, 'Invalid category selected.');
        }

        // Verify category type matches transaction type
        if ($category->type !== $validated['type']) {
            return back()->withErrors([
                'category_id' => 'Category type must match transaction type.'
            ])->withInput();
        }

        $transaction->update($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction updated successfully!');
    }

    public function destroy(Transaction $transaction)
    {
        $user = Auth::user();

        // Ensure transaction belongs to user's tenant
        if ($transaction->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access.');
        }

        // Authorization: Wife cannot delete Owner transactions
        if ($user->isWife()) {
            $transaction->load('user'); // Ensure user relationship is loaded
            if ($transaction->user->role === 'owner') {
                abort(403, 'You cannot modify Owner transactions.');
            }
            // Wife can only delete own or children's transactions
            if ($transaction->user_id !== $user->id && $transaction->user->role !== 'child') {
                abort(403, 'You can only delete your own and children transactions.');
            }
        }

        // Owner can delete all, Child can only delete their own
        if (!$user->isOwner() && !$user->isWife() && $transaction->user_id !== $user->id) {
            abort(403, 'You can only delete your own transactions.');
        }

        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction deleted successfully!');
    }
}

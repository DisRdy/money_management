<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * ReportController
 * 
 * Provides financial reports with role-based filtering capabilities
 * - Owner: See all family transactions (entire tenant)
 * - Wife: See own + child transactions only (NOT owner transactions)
 * - Child: Personal reports only (own transactions)
 */
class ReportController extends Controller
{
    /**
     * Display financial reports with optional filters
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Base query - role-based data isolation using visibleTo scope
        // - Owner: sees all tenant transactions
        // - Wife: sees own + child transactions (NOT owner)
        // - Child: sees only own transactions
        $query = Transaction::visibleTo($user);

        // Apply date filters
        if ($request->filled('date_from')) {
            $query->where('transaction_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('transaction_date', '<=', $request->date_to);
        }

        // Apply type filter (income/expense)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Apply category filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Get filtered transactions with relationships
        $transactions = $query->with(['user', 'category'])
            ->latest('transaction_date')
            ->get();

        // Calculate summary statistics
        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        // Get categories for filter dropdown (tenant-scoped)
        $categories = Category::where('tenant_id', $user->tenant_id)
            ->orderBy('name')
            ->get();

        return view('reports.index', compact(
            'transactions',
            'totalIncome',
            'totalExpense',
            'balance',
            'categories'
        ));
    }
}

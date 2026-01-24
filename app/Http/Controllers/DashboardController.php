<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * DashboardController
 * 
 * Provides role-based dashboard views with financial summaries
 * - Owner: Full family analytics
 * - Wife: Family financial summary
 * - Child: Personal balance and transactions
 */
class DashboardController extends Controller
{
    /**
     * Display the dashboard based on user role.
     */
    public function index()
    {
        $user = Auth::user();

        // Route to appropriate dashboard based on role
        if ($user->isOwner()) {
            return $this->ownerDashboard();
        } elseif ($user->isWife()) {
            return $this->wifeDashboard();
        } else {
            return $this->childDashboard();
        }
    }

    /**
     * Owner Dashboard - Complete family overview (all tenant transactions)
     */
    private function ownerDashboard()
    {
        $user = Auth::user();

        // Base query: Owner sees ALL tenant transactions
        $baseQuery = Transaction::visibleTo($user);

        // Count family members
        $totalMembers = User::where('tenant_id', $user->tenant_id)->count();

        // Calculate total income using role-based query
        $totalIncome = (clone $baseQuery)
            ->where('type', 'income')
            ->sum('amount');

        // Calculate total expense using role-based query
        $totalExpense = (clone $baseQuery)
            ->where('type', 'expense')
            ->sum('amount');

        // Calculate balance
        $balance = $totalIncome - $totalExpense;

        // Get recent transactions using the same base query
        $recentTransactions = (clone $baseQuery)
            ->with(['user', 'category'])
            ->latest('transaction_date')
            ->limit(10)
            ->get();

        // Get category-wise expense breakdown using role-based query
        $expenseByCategory = (clone $baseQuery)
            ->where('type', 'expense')
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->with('category')
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Chart data: Last 6 months income vs expense (role-based)
        $chartData = $this->getLast6MonthsDataForRole($user);

        // Category breakdown for pie chart (role-based)
        $categoryData = $this->getCategoryBreakdownForRole($user);

        return view('dashboard.owner', compact(
            'totalMembers',
            'totalIncome',
            'totalExpense',
            'balance',
            'recentTransactions',
            'expenseByCategory',
            'chartData',
            'categoryData'
        ));
    }

    private function getLast6MonthsData($tenantId)
    {
        $months = [];
        $income = [];
        $expense = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthLabel = $date->format('M Y');

            $months[] = $monthLabel;

            $incomeAmount = Transaction::where('tenant_id', $tenantId)
                ->where('type', 'income')
                ->whereYear('transaction_date', $date->year)
                ->whereMonth('transaction_date', $date->month)
                ->sum('amount');

            $expenseAmount = Transaction::where('tenant_id', $tenantId)
                ->where('type', 'expense')
                ->whereYear('transaction_date', $date->year)
                ->whereMonth('transaction_date', $date->month)
                ->sum('amount');

            $income[] = (float) $incomeAmount;
            $expense[] = (float) $expenseAmount;
        }

        return [
            'labels' => $months,
            'income' => $income,
            'expense' => $expense
        ];
    }

    private function getCategoryBreakdown($tenantId)
    {
        $categories = Transaction::where('tenant_id', $tenantId)
            ->where('type', 'expense')
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('category_id')
            ->with('category')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        return [
            'labels' => $categories->pluck('category.name')->toArray(),
            'data' => $categories->pluck('total')->map(fn($v) => (float) $v)->toArray()
        ];
    }

    /**
     * Wife Dashboard - Family financial summary (own + child transactions only)
     */
    private function wifeDashboard()
    {
        $user = Auth::user();

        // Base query: Wife sees own + child transactions (NOT owner)
        // This ensures ALL calculations use the same data set
        $baseQuery = Transaction::visibleTo($user);

        // Calculate total income using role-based query
        $totalIncome = (clone $baseQuery)
            ->where('type', 'income')
            ->sum('amount');

        // Calculate total expense using role-based query
        $totalExpense = (clone $baseQuery)
            ->where('type', 'expense')
            ->sum('amount');

        // Calculate balance
        $balance = $totalIncome - $totalExpense;

        // Get recent transactions using the same base query
        $recentTransactions = (clone $baseQuery)
            ->with(['user', 'category'])
            ->latest('transaction_date')
            ->limit(10)
            ->get();

        // Get expense by category this month using role-based query
        $expenseByCategory = (clone $baseQuery)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->with('category')
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->get();

        // Chart data: Last 6 months for wife (own + child only)
        $chartData = $this->getLast6MonthsDataForRole($user);

        return view('dashboard.wife', compact(
            'totalIncome',
            'totalExpense',
            'balance',
            'recentTransactions',
            'expenseByCategory',
            'chartData'
        ));
    }

    /**
     * Child Dashboard - Personal financial summary (own transactions only)
     */
    private function childDashboard()
    {
        $user = Auth::user();

        // Base query: Child sees only their own transactions
        $baseQuery = Transaction::visibleTo($user);

        // Calculate personal income using role-based query
        $personalIncome = (clone $baseQuery)
            ->where('type', 'income')
            ->sum('amount');

        // Calculate personal expense using role-based query
        $personalExpense = (clone $baseQuery)
            ->where('type', 'expense')
            ->sum('amount');

        // Calculate personal balance
        $personalBalance = $personalIncome - $personalExpense;

        // Get personal recent transactions using the same base query
        $recentTransactions = (clone $baseQuery)
            ->with(['category'])
            ->latest('transaction_date')
            ->limit(10)
            ->get();

        // Get personal expense by category using role-based query
        $expenseByCategory = (clone $baseQuery)
            ->where('type', 'expense')
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->with('category')
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Chart data: Personal spending breakdown (role-based)
        $categoryData = $this->getPersonalCategoryBreakdownForRole($user);

        return view('dashboard.child', compact(
            'personalIncome',
            'personalExpense',
            'personalBalance',
            'recentTransactions',
            'expenseByCategory',
            'categoryData'
        ));
    }

    private function getPersonalCategoryBreakdown($userId)
    {
        $categories = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('category_id')
            ->with('category')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        return [
            'labels' => $categories->pluck('category.name')->toArray(),
            'data' => $categories->pluck('total')->map(fn($v) => (float) $v)->toArray()
        ];
    }

    /**
     * Get last 6 months income/expense trend (role-based)
     */
    private function getLast6MonthsDataForRole($user)
    {
        $labels = [];
        $income = [];
        $expense = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->format('M Y');

            $income[] = (float) Transaction::visibleTo($user)
                ->where('type', 'income')
                ->whereMonth('transaction_date', $date->month)
                ->whereYear('transaction_date', $date->year)
                ->sum('amount');

            $expense[] = (float) Transaction::visibleTo($user)
                ->where('type', 'expense')
                ->whereMonth('transaction_date', $date->month)
                ->whereYear('transaction_date', $date->year)
                ->sum('amount');
        }

        return [
            'labels' => $labels,
            'income' => $income,
            'expense' => $expense,
        ];
    }

    /**
     * Get category breakdown for expenses (role-based)
     */
    private function getCategoryBreakdownForRole($user)
    {
        $categories = Transaction::visibleTo($user)
            ->where('type', 'expense')
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('category_id')
            ->with('category')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        return [
            'labels' => $categories->pluck('category.name')->toArray(),
            'data' => $categories->pluck('total')->map(fn($v) => (float) $v)->toArray()
        ];
    }

    /**
     * Get personal category breakdown for child (role-based)
     */
    private function getPersonalCategoryBreakdownForRole($user)
    {
        $categories = Transaction::visibleTo($user)
            ->where('type', 'expense')
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('category_id')
            ->with('category')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        return [
            'labels' => $categories->pluck('category.name')->toArray(),
            'data' => $categories->pluck('total')->map(fn($v) => (float) $v)->toArray()
        ];
    }
}

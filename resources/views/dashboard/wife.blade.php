<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Family Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Success Message --}}
            @if (session('success'))
                <div class="mb-4 bg-green-100 dark:bg-green-900/50 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded relative"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                {{-- Total Income --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Family Income</div>
                        <div class="mt-2 text-3xl font-semibold text-green-600 dark:text-green-400">
                            {{ auth()->user()->tenant->currency_symbol }}
                            {{ number_format($totalIncome, 0, ',', '.') }}
                        </div>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Total earnings</p>
                    </div>
                </div>

                {{-- Total Expense --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Family Expenses
                        </div>
                        <div class="mt-2 text-3xl font-semibold text-red-600 dark:text-red-400">
                            {{ auth()->user()->tenant->currency_symbol }}
                            {{ number_format($totalExpense, 0, ',', '.') }}
                        </div>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Total spending</p>
                    </div>
                </div>

                {{-- Balance --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Balance</div>
                        <div
                            class="mt-2 text-3xl font-semibold {{ $balance >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ auth()->user()->tenant->currency_symbol }} {{ number_format($balance, 0, ',', '.') }}
                        </div>
                        <p
                            class="mt-2 text-xs {{ $balance >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $balance >= 0 ? '✓ Healthy balance' : '⚠ Deficit detected' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Monthly Trend Chart --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700 mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Family Financial Trend (Last
                        6 Months)</h3>
                    <div style="height: 350px;">
                        <canvas id="monthlyTrendChart" data-chart-data="{{ json_encode($chartData) }}">
                        </canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Recent Transactions --}}
                <div class="lg:col-span-2">
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent Family
                                    Transactions</h3>
                                <a href="{{ route('transactions.index') }}"
                                    class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">View
                                    All</a>
                            </div>

                            @if($recentTransactions->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                                            <tr>
                                                <th scope="col"
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                    Date</th>
                                                <th scope="col"
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                    User</th>
                                                <th scope="col"
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                    Category</th>
                                                <th scope="col"
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                    Description</th>
                                                <th scope="col"
                                                    class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                    Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody
                                            class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($recentTransactions as $transaction)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                                    <td
                                                        class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $transaction->transaction_date->format('d M Y') }}
                                                    </td>
                                                    <td
                                                        class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $transaction->user->name }}
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                    {{ $transaction->type === 'income' ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300' }}">
                                                            {{ $transaction->category->name }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                                        {{ Str::limit($transaction->description, 30) }}
                                                    </td>
                                                    <td
                                                        class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium
                                                                {{ $transaction->type === 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                        {{ $transaction->type === 'income' ? '+' : '-' }}
                                                        {{ auth()->user()->tenant->currency_symbol }}
                                                        {{ number_format($transaction->amount, 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No transactions
                                        yet</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new
                                        transaction.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('transactions.create') }}"
                                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                            Add First Transaction
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Expense Breakdown (This Month) --}}
                <div class="lg:col-span-1">
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">This Month's Expenses
                                </h3>
                            </div>

                            @if($expenseByCategory->count() > 0)
                                <div class="space-y-4">
                                    @php
                                        $totalMonthExpense = $expenseByCategory->sum('total');
                                    @endphp
                                    @foreach($expenseByCategory as $item)
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $item->category->name }}</div>
                                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-2">
                                                    <div class="bg-red-600 dark:bg-red-500 h-2 rounded-full"
                                                        style="width: {{ $totalMonthExpense > 0 ? ($item->total / $totalMonthExpense * 100) : 0 }}%">
                                                    </div>
                                                </div>
                                                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $totalMonthExpense > 0 ? number_format(($item->total / $totalMonthExpense * 100), 1) : 0 }}%
                                                </div>
                                            </div>
                                            <div class="ml-4 text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                {{ auth()->user()->tenant->currency_symbol }}
                                                {{ number_format($item->total, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <div class="flex justify-between items-center">
                                            <span
                                                class="text-sm font-semibold text-gray-700 dark:text-gray-300">Total</span>
                                            <span class="text-sm font-bold text-red-600 dark:text-red-400">
                                                {{ auth()->user()->tenant->currency_symbol }}
                                                {{ number_format($totalMonthExpense, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-10 w-10 text-gray-400 dark:text-gray-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No expenses this month</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Quick Tip --}}
                    <div
                        class="mt-4 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-blue-800 dark:text-blue-300">Tip</h4>
                                    <p class="mt-1 text-xs text-blue-700 dark:text-blue-400">Track expenses regularly to
                                        maintain a healthy
                                        family
                                        budget!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div
                class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Quick Actions</h3>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('transactions.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Add Transaction
                        </a>
                        <a href="{{ route('transactions.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            View All Transactions
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
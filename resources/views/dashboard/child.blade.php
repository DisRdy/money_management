<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('My Dashboard') }}
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

            {{-- Welcome Message --}}
            <div class="mb-6 bg-gradient-to-r from-blue-500 to-cyan-500 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    <h3 class="text-2xl font-bold">Hey {{ Auth::user()->name }}! 🎓</h3>
                    <p class="mt-2 text-blue-100">Here's your personal savings tracker</p>
                </div>
            </div>

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                {{-- Personal Income (Allowance) --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-100 dark:bg-green-900/50 rounded-md p-3">
                                <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">My Income
                                </div>
                                <div class="mt-1 text-2xl font-semibold text-green-600 dark:text-green-400">
                                    {{ auth()->user()->tenant->currency_symbol }}
                                    {{ number_format($personalIncome, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">Allowance & earnings</p>
                    </div>
                </div>

                {{-- Personal Expense --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-red-100 dark:bg-red-900/50 rounded-md p-3">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">My Spending
                                </div>
                                <div class="mt-1 text-2xl font-semibold text-red-600 dark:text-red-400">
                                    {{ auth()->user()->tenant->currency_symbol }}
                                    {{ number_format($personalExpense, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">Total expenses</p>
                    </div>
                </div>

                {{-- Personal Balance (Savings) --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div
                                class="flex-shrink-0 {{ $personalBalance >= 0 ? 'bg-blue-100 dark:bg-blue-900/50' : 'bg-orange-100 dark:bg-orange-900/50' }} rounded-md p-3">
                                <svg class="h-6 w-6 {{ $personalBalance >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-orange-600 dark:text-orange-400' }}"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">My Savings
                                </div>
                                <div
                                    class="mt-1 text-2xl font-semibold {{ $personalBalance >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-orange-600 dark:text-orange-400' }}">
                                    {{ auth()->user()->tenant->currency_symbol }}
                                    {{ number_format($personalBalance, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                        <p
                            class="mt-3 text-xs {{ $personalBalance >= 0 ? 'text-green-600 dark:text-green-400' : 'text-orange-600 dark:text-orange-400' }}">
                            @if($personalBalance >= 0)
                                🎉 Great job saving!
                            @else
                                ⚠ You're overspending
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Savings Progress --}}
            @if($personalIncome > 0)
                <div
                    class="mb-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Savings Progress</h3>
                        @php
                            $savingsRate = $personalIncome > 0 ? (($personalIncome - $personalExpense) / $personalIncome) * 100 : 0;
                            $savingsRate = max(0, min(100, $savingsRate)); // Clamp between 0-100
                        @endphp
                        <div class="relative pt-1">
                            <div class="flex mb-2 items-center justify-between">
                                <div>
                                    <span
                                        class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full {{ $savingsRate >= 50 ? 'text-green-600 dark:text-green-400 bg-green-200 dark:bg-green-900/50' : ($savingsRate >= 20 ? 'text-yellow-600 dark:text-yellow-400 bg-yellow-200 dark:bg-yellow-900/50' : 'text-red-600 dark:text-red-400 bg-red-200 dark:bg-red-900/50') }}">
                                        Savings Rate
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-semibold inline-block text-gray-600 dark:text-gray-400">
                                        {{ number_format($savingsRate, 1) }}%
                                    </span>
                                </div>
                            </div>
                            <div class="overflow-hidden h-3 mb-4 text-xs flex rounded bg-gray-200 dark:bg-gray-700">
                                <div style="width:{{ $savingsRate }}%"
                                    class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center {{ $savingsRate >= 50 ? 'bg-green-500' : ($savingsRate >= 20 ? 'bg-yellow-500' : 'bg-red-500') }} transition-all duration-500">
                                </div>
                            </div>
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                @if($savingsRate >= 50)
                                    🌟 Excellent! You're saving more than half of your allowance!
                                @elseif($savingsRate >= 20)
                                    👍 Good job! Try to save a bit more.
                                @else
                                    💡 Try to reduce spending and save at least 20% of your allowance.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Personal Spending Chart --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700 mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">My Spending by Category</h3>
                    <div style="height: 350px;">
                        <canvas id="categoryPieChart" data-chart-data="{{ json_encode($categoryData) }}">
                        </canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Recent Personal Transactions --}}
                <div class="lg:col-span-2">
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">My Recent
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
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                    {{ $transaction->type === 'income' ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300' }}">
                                                            {{ $transaction->category->name }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                                        {{ Str::limit($transaction->description, 40) }}
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
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Start tracking your allowance
                                        and spending!</p>
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

                {{-- Spending Breakdown --}}
                <div class="lg:col-span-1">
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Spending Breakdown
                            </h3>

                            @if($expenseByCategory->count() > 0)
                                <div class="space-y-4">
                                    @foreach($expenseByCategory as $item)
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $item->category->name }}
                                                </div>
                                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-2">
                                                    <div class="bg-red-600 dark:bg-red-500 h-2 rounded-full"
                                                        style="width: {{ $personalExpense > 0 ? ($item->total / $personalExpense * 100) : 0 }}%">
                                                    </div>
                                                </div>
                                                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $personalExpense > 0 ? number_format(($item->total / $personalExpense * 100), 1) : 0 }}%
                                                </div>
                                            </div>
                                            <div class="ml-4 text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                {{ auth()->user()->tenant->currency_symbol }}
                                                {{ number_format($item->total, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-10 w-10 text-gray-400 dark:text-gray-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No expenses tracked yet</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Savings Tip --}}
                    <div
                        class="mt-4 bg-gradient-to-br from-yellow-50 to-orange-50 dark:from-yellow-900/30 dark:to-orange-900/30 border border-yellow-200 dark:border-yellow-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-500 dark:text-yellow-400" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-300">Pro Tip</h4>
                                    <p class="mt-1 text-xs text-yellow-700 dark:text-yellow-400">Save at least 20% of
                                        your allowance every
                                        month to
                                        build good financial habits!</p>
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
                            View My Transactions
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
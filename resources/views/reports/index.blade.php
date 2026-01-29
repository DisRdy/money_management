<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Financial Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Page Header --}}
            <div class="mb-6 bg-gradient-to-r from-indigo-600 to-purple-600 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <h3 class="text-2xl font-bold">Financial Reports</h3>
                            <p class="mt-2 text-indigo-100">
                                @if(Auth::user()->isChild())
                                    Your personal financial summary and transaction history
                                @else
                                    Your family's financial summary and transaction history
                                @endif
                            </p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <div class="text-sm text-indigo-200">
                                Report generated for
                            </div>
                            <div class="text-lg font-semibold">
                                @if(Auth::user()->isChild())
                                    {{ Auth::user()->name }}
                                @else
                                    {{ Auth::user()->tenant->name ?? 'Family' }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter Section --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter Reports
                    </h3>

                    <form method="GET" action="{{ route('reports.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            {{-- Date From --}}
                            <div>
                                <label for="date_from"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    From Date
                                </label>
                                <input type="date" name="date_from" id="date_from"
                                    value="{{ request('date_from') ?? '' }}"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>

                            {{-- Date To --}}
                            <div>
                                <label for="date_to"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    To Date
                                </label>
                                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') ?? '' }}"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>

                            {{-- Type Filter --}}
                            <div>
                                <label for="type"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Type
                                </label>
                                <select name="type" id="type"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <option value="">All Types</option>
                                    <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>Income
                                    </option>
                                    <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>Expense
                                    </option>
                                </select>
                            </div>

                            {{-- Category Filter --}}
                            <div>
                                <label for="category"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Category
                                </label>
                                <select name="category_id" id="category"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <option value="">All Categories</option>
                                    @if(isset($categories))
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        {{-- Filter Actions --}}
                        <div class="flex flex-wrap gap-3 pt-2">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Apply Filters
                            </button>
                            <a href="{{ route('reports.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Reset Filters
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                {{-- Total Income Card --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500 dark:border-green-400">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <div class="bg-green-100 dark:bg-green-900/50 rounded-lg p-3 mr-3">
                                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total
                                            Income</p>
                                        <p class="mt-1 text-2xl font-bold text-green-600 dark:text-green-400">
                                            Rp {{ number_format($totalIncome ?? 0, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Expense Card --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-red-500 dark:border-red-400">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <div class="bg-red-100 dark:bg-red-900/50 rounded-lg p-3 mr-3">
                                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total
                                            Expense</p>
                                        <p class="mt-1 text-2xl font-bold text-red-600 dark:text-red-400">
                                            Rp {{ number_format($totalExpense ?? 0, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Net Balance Card --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border-l-4 {{ ($balance ?? 0) >= 0 ? 'border-blue-500 dark:border-blue-400' : 'border-orange-500 dark:border-orange-400' }}">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <div
                                        class="bg-{{ ($balance ?? 0) >= 0 ? 'blue' : 'orange' }}-100 dark:bg-{{ ($balance ?? 0) >= 0 ? 'blue' : 'orange' }}-900/50 rounded-lg p-3 mr-3">
                                        <svg class="w-6 h-6 text-{{ ($balance ?? 0) >= 0 ? 'blue' : 'orange' }}-600 dark:text-{{ ($balance ?? 0) >= 0 ? 'blue' : 'orange' }}-400"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Net
                                            Balance</p>
                                        <p
                                            class="mt-1 text-2xl font-bold {{ ($balance ?? 0) >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-orange-600 dark:text-orange-400' }}">
                                            Rp {{ number_format($balance ?? 0, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Export Actions --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200 dark:border-gray-700">
                <div class="p-4">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            <span
                                class="font-semibold text-gray-900 dark:text-gray-100">{{ isset($transactions) ? $transactions->count() : 0 }}</span>
                            {{ isset($transactions) && $transactions->count() === 1 ? 'transaction' : 'transactions' }}
                            found
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <button type="button"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                Export PDF
                            </button>
                            <button type="button"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Export Excel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Report Table --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Transaction Details
                    </h3>

                    @if(isset($transactions) && $transactions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <th scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Date
                                        </th>
                                        @if(Auth::user()->isOwner() || Auth::user()->isWife())
                                            <th scope="col"
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                User
                                            </th>
                                        @endif
                                        <th scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Type
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Category
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Description
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Amount
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($transactions as $transaction)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $transaction->transaction_date->format('d M Y') }}
                                            </td>
                                            @if(Auth::user()->isOwner() || Auth::user()->isWife())
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                    {{ $transaction->user->name ?? '-' }}
                                                </td>
                                            @endif
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                <span
                                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                        {{ $transaction->type === 'income' ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300' }}">
                                                    {{ ucfirst($transaction->type) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $transaction->category->name ?? '-' }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                                {{ Str::limit($transaction->description, 50) }}
                                            </td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-right font-semibold
                                                                    {{ $transaction->type === 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                {{ $transaction->type === 'income' ? '+' : '-' }} Rp
                                                {{ number_format($transaction->amount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <td colspan="{{ Auth::user()->isOwner() || Auth::user()->isWife() ? '5' : '4' }}"
                                            class="px-4 py-3 text-right text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            Total Balance:
                                        </td>
                                        <td
                                            class="px-4 py-3 text-right text-sm font-bold {{ ($balance ?? 0) >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            Rp {{ number_format($balance ?? 0, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        {{-- Empty State --}}
                        <div class="text-center py-16">
                            <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">No transactions found</h3>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                @if(request()->hasAny(['date_from', 'date_to', 'type', 'category_id']))
                                    No transactions match your current filter criteria. Try adjusting your filters.
                                @else
                                    You haven't recorded any transactions yet. Start adding transactions to see your financial
                                    report.
                                @endif
                            </p>
                            <div class="mt-6 flex justify-center gap-3">
                                @if(request()->hasAny(['date_from', 'date_to', 'type', 'category_id']))
                                    <a href="{{ route('reports.index') }}"
                                        class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                        Clear Filters
                                    </a>
                                @endif
                                <a href="{{ route('transactions.create') }}"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Add Transaction
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Info Footer --}}
            <div
                class="mt-6 bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400 dark:text-yellow-300" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Report Information</h4>
                        <p class="mt-1 text-xs text-yellow-700 dark:text-yellow-300">
                            @if(Auth::user()->isChild())
                                This report shows your personal transactions only. Family-wide reports are available to
                                Owner and Wife roles.
                            @else
                                This report includes all family transactions. Use filters to narrow down specific time
                                periods or categories.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
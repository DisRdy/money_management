<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Owner Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Success Message --}}
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                {{-- Total Members --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 uppercase">Total Members</div>
                        <div class="mt-2 text-3xl font-semibold text-gray-900">{{ $totalMembers }}</div>
                    </div>
                </div>

                {{-- Total Income --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 uppercase">Total Income</div>
                        <div class="mt-2 text-3xl font-semibold text-green-600">Rp
                            {{ number_format($totalIncome, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                {{-- Total Expense --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 uppercase">Total Expense</div>
                        <div class="mt-2 text-3xl font-semibold text-red-600">Rp
                            {{ number_format($totalExpense, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                {{-- Balance --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 uppercase">Balance</div>
                        <div
                            class="mt-2 text-3xl font-semibold {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            Rp {{ number_format($balance, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Charts Section --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                {{-- Income vs Expense Trend Chart --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Financial Trend (Last 6 Months)</h3>
                        <div style="height: 300px;">
                            <canvas id="incomeExpenseChart" data-chart-data="{{ json_encode($chartData) }}">
                            </canvas>
                        </div>
                    </div>
                </div>

                {{-- Category Breakdown Pie Chart --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Expense Breakdown by Category</h3>
                        <div style="height: 300px;">
                            <canvas id="categoryPieChart" data-chart-data="{{ json_encode($categoryData) }}">
                            </canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Recent Transactions --}}
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Recent Transactions</h3>
                                <a href="{{ route('transactions.index') }}"
                                    class="text-sm text-indigo-600 hover:text-indigo-900">View All</a>
                            </div>

                            @if($recentTransactions->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col"
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Date</th>
                                                <th scope="col"
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    User</th>
                                                <th scope="col"
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Category</th>
                                                <th scope="col"
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Description</th>
                                                <th scope="col"
                                                    class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($recentTransactions as $transaction)
                                                <tr>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                        {{ $transaction->transaction_date->format('d M Y') }}
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                        {{ $transaction->user->name }}
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                            {{ $transaction->type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                            {{ $transaction->category->name }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-900">
                                                        {{ Str::limit($transaction->description, 30) }}
                                                    </td>
                                                    <td
                                                        class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium
                                                                        {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                                        {{ $transaction->type === 'income' ? '+' : '-' }} Rp
                                                        {{ number_format($transaction->amount, 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-gray-500 text-center py-8">No transactions yet.</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Top Expense Categories --}}
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Top Expenses</h3>
                                <a href="{{ route('categories.index') }}"
                                    class="text-sm text-indigo-600 hover:text-indigo-900">View All</a>
                            </div>

                            @if($expenseByCategory->count() > 0)
                                <div class="space-y-3">
                                    @foreach($expenseByCategory as $item)
                                        <div class="flex justify-between items-center">
                                            <div class="flex-1">
                                                <div class="text-sm font-medium text-gray-900">{{ $item->category->name }}</div>
                                                <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                                    <div class="bg-red-600 h-2 rounded-full"
                                                        style="width: {{ $totalExpense > 0 ? ($item->total / $totalExpense * 100) : 0 }}%">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ml-4 text-sm font-semibold text-gray-900">
                                                Rp {{ number_format($item->total, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-center py-8">No expenses yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('transactions.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Add Transaction
                        </a>
                        <a href="{{ route('categories.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Add Category
                        </a>
                        <a href="{{ route('transactions.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            View All Transactions
                        </a>
                        <a href="{{ route('categories.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            View All Categories
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
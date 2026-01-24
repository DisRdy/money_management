<x-app-layout>
    {{-- Store categories data for modal --}}
    <script>
        window.editModalCategories = @json($categories->map(function($cat) {
            return ['id' => $cat->id, 'name' => $cat->name, 'type' => $cat->type];
        }));
    </script>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Transactions') }}
            </h2>
            <a href="{{ route('transactions.create') }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Add New Transaction
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Filters --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter Transactions</h3>
                    <form method="GET" action="{{ route('transactions.index') }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            {{-- Type Filter --}}
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                                <select name="type" id="type"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">All Types</option>
                                    <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>Income
                                    </option>
                                    <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>Expense
                                    </option>
                                </select>
                            </div>

                            {{-- Category Filter --}}
                            <div>
                                <label for="category_id"
                                    class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                <select name="category_id" id="category_id"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }} ({{ ucfirst($category->type) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Start Date Filter --}}
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start
                                    Date</label>
                                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            {{-- End Date Filter --}}
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End
                                    Date</label>
                                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-4 flex justify-between items-center gap-2">
                            <div class="flex gap-2">
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Apply Filters
                                </button>
                                <a href="{{ route('transactions.index') }}"
                                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Clear Filters
                                </a>
                            </div>
                            <a href="{{ route('transactions.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                + Add Transaction
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Transactions Table --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">All Transactions</h3>

                    @if($transactions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date</th>
                                        <th scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Type</th>
                                        @if(!auth()->user()->isChild())
                                            <th scope="col"
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                User</th>
                                        @endif
                                        <th scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Category</th>
                                        <th scope="col"
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Description</th>
                                        <th scope="col"
                                            class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Amount</th>
                                        <th scope="col"
                                            class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($transactions as $transaction)
                                        <tr>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ $transaction->transaction_date->format('d M Y') }}</div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                            {{ $transaction->type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ ucfirst($transaction->type) }}
                                                </span>
                                            </td>
                                            @if(!auth()->user()->isChild())
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $transaction->user->name }}</div>
                                                </td>
                                            @endif
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $transaction->category->name }}</div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="text-sm text-gray-900">
                                                    {{ Str::limit($transaction->description, 40) }}</div>
                                                @if($transaction->notes)
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        {{ Str::limit($transaction->notes, 40) }}</div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                                <div
                                                    class="text-sm font-semibold 
                                                            {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $transaction->type === 'income' ? '+' : '-' }} Rp
                                                    {{ number_format($transaction->amount, 0, ',', '.') }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex justify-end gap-2">
                                                    <a href="{{ route('transactions.show', $transaction) }}"
                                                        class="text-indigo-600 hover:text-indigo-900">View</a>

                                                    @php
                                                        $canModify = false;
                                                        $user = auth()->user();
                                                        
                                                        // Owner can modify all transactions
                                                        if ($user->isOwner()) {
                                                            $canModify = true;
                                                        }
                                                        // Wife can modify own and children's transactions (NOT owner)
                                                        elseif ($user->isWife()) {
                                                            if ($transaction->user_id === $user->id) {
                                                                $canModify = true; // Her own transaction
                                                            } elseif ($transaction->user->role === 'child') {
                                                                $canModify = true; // Child transaction
                                                            }
                                                            // else: Owner transaction, cannot modify
                                                        }
                                                        // Child can only modify own transactions
                                                        elseif ($transaction->user_id === $user->id) {
                                                            $canModify = true;
                                                        }
                                                    @endphp

                                                    @if($canModify)
                                                        <button type="button" 
                                                            onclick="openEditModal('transaction', {{ $transaction->id }}, {{ json_encode(['id' => $transaction->id, 'type' => $transaction->type, 'category_id' => $transaction->category_id, 'amount' => $transaction->amount, 'transaction_date' => $transaction->transaction_date->format('Y-m-d'), 'description' => $transaction->description, 'notes' => $transaction->notes]) }})"
                                                            class="text-yellow-600 hover:text-yellow-900 cursor-pointer">Edit</button>

                                                        <form action="{{ route('transactions.destroy', $transaction) }}"
                                                            method="POST" class="inline"
                                                            data-confirm-delete="this transaction ({{ Str::limit($transaction->description, 30) }})">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="text-red-600 hover:text-red-900">Delete</button>
                                                        </form>
                                                    @else
                                                        <span class="text-gray-400 text-xs italic" title="Read-only (Owner transaction)">View only</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-4">
                            {{ $transactions->links() }}
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No transactions found.</p>
                        <div class="text-center">
                            <a href="{{ route('transactions.create') }}"
                                class="text-indigo-600 hover:text-indigo-900 text-sm">
                                Create your first transaction
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Summary Statistics --}}
            @if($transactions->count() > 0)
                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500 uppercase">Total Income (Filtered)</div>
                            <div class="mt-2 text-2xl font-semibold text-green-600">
                                Rp {{ number_format($transactions->where('type', 'income')->sum('amount'), 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500 uppercase">Total Expense (Filtered)</div>
                            <div class="mt-2 text-2xl font-semibold text-red-600">
                                Rp {{ number_format($transactions->where('type', 'expense')->sum('amount'), 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500 uppercase">Net (Filtered)</div>
                            @php
                                $net = $transactions->where('type', 'income')->sum('amount') - $transactions->where('type', 'expense')->sum('amount');
                            @endphp
                            <div class="mt-2 text-2xl font-semibold {{ $net >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                Rp {{ number_format($net, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
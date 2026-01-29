<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Category Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Category Details Card --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $category->name }}</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Created by
                                {{ $category->user->name }}</p>
                        </div>
                        <span
                            class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                            {{ $category->type === 'income' ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300' }}">
                            {{ ucfirst($category->type) }}
                        </span>
                    </div>

                    {{-- Category Information --}}
                    <div class="space-y-4">
                        @if($category->description)
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Description</label>
                                <p class="text-base text-gray-900 dark:text-gray-100">{{ $category->description }}</p>
                            </div>
                        @endif

                        <div
                            class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total
                                    Transactions</label>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $category->transactions->count() }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Created</label>
                                <p class="text-base text-gray-900 dark:text-gray-100">
                                    {{ $category->created_at->format('d M Y') }}</p>
                            </div>
                            @if($category->created_at != $category->updated_at)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Last
                                        Updated</label>
                                    <p class="text-base text-gray-900 dark:text-gray-100">
                                        {{ $category->updated_at->format('d M Y') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Transactions Using This Category --}}
            @if($category->transactions->count() > 0)
                <div
                    class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Recent Transactions</h3>
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
                                            Description</th>
                                        <th scope="col"
                                            class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Amount</th>
                                        <th scope="col"
                                            class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($category->transactions->sortByDesc('transaction_date')->take(10) as $transaction)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $transaction->transaction_date->format('d M Y') }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $transaction->user->name }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                                {{ Str::limit($transaction->description, 40) }}
                                            </td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-right font-semibold
                                                                                {{ $transaction->type === 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                {{ $transaction->type === 'income' ? '+' : '-' }} Rp
                                                {{ number_format($transaction->amount, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('transactions.show', $transaction) }}"
                                                    class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($category->transactions->count() > 10)
                            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400 text-center">
                                Showing 10 most recent transactions.
                                <a href="{{ route('transactions.index', ['category_id' => $category->id]) }}"
                                    class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">View
                                    all
                                    {{ $category->transactions->count() }} transactions</a>
                            </p>
                        @endif
                    </div>
                </div>
            @else
                <div
                    class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <p class="text-gray-500 dark:text-gray-400 text-center py-8">No transactions using this category
                            yet.</p>
                    </div>
                </div>
            @endif

            {{-- Action Buttons --}}
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('categories.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    ← Back to List
                </a>

                @if(auth()->user()->isOwner())
                    <button type="button"
                        onclick="openEditModal('category', {{ $category->id }}, {{ json_encode(['id' => $category->id, 'name' => $category->name, 'type' => $category->type, 'description' => $category->description, 'transactions_count' => $category->transactions->count()]) }})"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        Edit Category
                    </button>

                    @if($category->transactions->count() === 0)
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline"
                            data-confirm-delete="this category ({{ $category->name }})">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Delete Category
                            </button>
                        </form>
                    @else
                        <button type="button" disabled
                            class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-500 dark:text-gray-400 uppercase tracking-widest cursor-not-allowed"
                            title="Cannot delete category with existing transactions">
                            Delete Category
                        </button>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
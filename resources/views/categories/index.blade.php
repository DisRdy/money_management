<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Categories') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Page Header --}}
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Manage Transaction Categories</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Organize your income and expense
                        transactions by creating
                        custom categories</p>
                </div>
                @if(auth()->user()->isOwner())
                    <a href="{{ route('categories.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Category
                    </a>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Income Categories --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                            <span class="inline-block w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                            Income Categories
                        </h3>

                        @if($incomeCategories->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                                        <tr>
                                            <th scope="col"
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Name</th>
                                            <th scope="col"
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Description</th>
                                            <th scope="col"
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Created By</th>
                                            @if(auth()->user()->isOwner())
                                                <th scope="col"
                                                    class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                    Actions</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($incomeCategories as $category)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $category->name }}</div>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $category->description ?? '-' }}</div>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $category->user->name }}</div>
                                                </td>
                                                @if(auth()->user()->isOwner())
                                                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                                        <div class="flex justify-end gap-2">
                                                            <a href="{{ route('categories.show', $category) }}"
                                                                class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">View</a>
                                                            <button type="button"
                                                                onclick="openEditModal('category', {{ $category->id }}, {{ json_encode(['id' => $category->id, 'name' => $category->name, 'type' => $category->type, 'description' => $category->description, 'transactions_count' => $category->transactions->count()]) }})"
                                                                class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300 cursor-pointer">Edit</button>
                                                            <form action="{{ route('categories.destroy', $category) }}"
                                                                method="POST" class="inline"
                                                                data-confirm-delete="this category ({{ $category->name }})">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">Delete</button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-center py-8">No income categories yet.</p>
                            @if(auth()->user()->isOwner())
                                <div class="text-center">
                                    <a href="{{ route('categories.create') }}"
                                        class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 text-sm">
                                        Create your first income category
                                    </a>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- Expense Categories --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                            <span class="inline-block w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                            Expense Categories
                        </h3>

                        @if($expenseCategories->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                                        <tr>
                                            <th scope="col"
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Name</th>
                                            <th scope="col"
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Description</th>
                                            <th scope="col"
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Created By</th>
                                            @if(auth()->user()->isOwner())
                                                <th scope="col"
                                                    class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                    Actions</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($expenseCategories as $category)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $category->name }}</div>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $category->description ?? '-' }}</div>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $category->user->name }}</div>
                                                </td>
                                                @if(auth()->user()->isOwner())
                                                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                                        <div class="flex justify-end gap-2">
                                                            <a href="{{ route('categories.show', $category) }}"
                                                                class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">View</a>
                                                            <button type="button"
                                                                onclick="openEditModal('category', {{ $category->id }}, {{ json_encode(['id' => $category->id, 'name' => $category->name, 'type' => $category->type, 'description' => $category->description, 'transactions_count' => $category->transactions->count()]) }})"
                                                                class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300 cursor-pointer">Edit</button>
                                                            <form action="{{ route('categories.destroy', $category) }}"
                                                                method="POST" class="inline"
                                                                data-confirm-delete="this category ({{ $category->name }})">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">Delete</button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-center py-8">No expense categories yet.</p>
                            @if(auth()->user()->isOwner())
                                <div class="text-center">
                                    <a href="{{ route('categories.create') }}"
                                        class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 text-sm">
                                        Create your first expense category
                                    </a>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            {{-- Information Box for Non-Owners --}}
            @if(!auth()->user()->isOwner())
                <div class="mt-6 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400 dark:text-blue-300" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                You can view all categories, but only the family owner can create, edit, or delete
                                categories.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
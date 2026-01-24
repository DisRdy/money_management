<x-app-layout>
    @php
        $categoriesForModal = \App\Models\Category::where('tenant_id', auth()->user()->tenant_id)
            ->get()
            ->map(function ($cat) {
                return ['id' => $cat->id, 'name' => $cat->name, 'type' => $cat->type];
            });
    @endphp
    <script>
        window.editModalCategories = @json($categoriesForModal);
    </script>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transaction Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            {{-- Transaction Details Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $transaction->description }}</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ $transaction->transaction_date->format('l, d F Y') }}
                            </p>
                        </div>
                        <span
                            class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                            {{ $transaction->type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($transaction->type) }}
                        </span>
                    </div>

                    {{-- Amount Display --}}
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <div class="text-sm font-medium text-gray-500 uppercase mb-1">Amount</div>
                        <div class="text-4xl font-bold 
                            {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $transaction->type === 'income' ? '+' : '-' }} Rp
                            {{ number_format($transaction->amount, 0, ',', '.') }}
                        </div>
                    </div>

                    {{-- Transaction Information --}}
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Category --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Category</label>
                                <p class="text-base text-gray-900">{{ $transaction->category->name }}</p>
                                @if($transaction->category->description)
                                    <p class="text-sm text-gray-500 mt-1">{{ $transaction->category->description }}</p>
                                @endif
                            </div>

                            {{-- Created By --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Created By</label>
                                <p class="text-base text-gray-900">{{ $transaction->user->name }}</p>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Description</label>
                            <p class="text-base text-gray-900">{{ $transaction->description }}</p>
                        </div>

                        {{-- Notes --}}
                        @if($transaction->notes)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Notes</label>
                                <p class="text-base text-gray-900 whitespace-pre-line">{{ $transaction->notes }}</p>
                            </div>
                        @endif

                        {{-- Timestamps --}}
                        <div class="pt-4 border-t border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-500">Created:</span>
                                    <span
                                        class="text-gray-900">{{ $transaction->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                @if($transaction->created_at != $transaction->updated_at)
                                    <div>
                                        <span class="font-medium text-gray-500">Last Updated:</span>
                                        <span
                                            class="text-gray-900">{{ $transaction->updated_at->format('d M Y, H:i') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('transactions.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    ← Back to List
                </a>

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
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Edit Transaction
                    </button>

                    <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline"
                        data-confirm-delete="this transaction ({{ Str::limit($transaction->description, 30) }})">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Delete Transaction
                        </button>
                    </form>
                @else
                    <div class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-500 uppercase tracking-widest cursor-not-allowed"
                        title="Read-only (Owner transaction)">
                        🔒 View Only
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
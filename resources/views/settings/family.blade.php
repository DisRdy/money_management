<x-app-layout>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Family Settings</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Manage your family (tenant) configuration settings.</p>
    </div>

    <div class="max-w-3xl">
        <!-- Family Settings Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Family Configuration
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">These settings apply to all members in your
                    family.</p>
            </div>

            <form method="POST" action="{{ route('settings.family.update') }}" class="p-6 space-y-6">
                @csrf

                <!-- Family Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Family Name
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $tenant->name) }}"
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                        placeholder="Enter your family name">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Currency Display -->
                <div>
                    <label for="currency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Currency Display
                    </label>
                    <select name="currency" id="currency"
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="IDR" {{ old('currency', $tenant->currency ?? 'IDR') === 'IDR' ? 'selected' : '' }}>
                            IDR - Indonesian Rupiah (Rp)
                        </option>
                        <option value="USD" {{ old('currency', $tenant->currency ?? 'IDR') === 'USD' ? 'selected' : '' }}>
                            USD - US Dollar ($)
                        </option>
                        <option value="EUR" {{ old('currency', $tenant->currency ?? 'IDR') === 'EUR' ? 'selected' : '' }}>
                            EUR - Euro (€)
                        </option>
                        <option value="GBP" {{ old('currency', $tenant->currency ?? 'IDR') === 'GBP' ? 'selected' : '' }}>
                            GBP - British Pound (£)
                        </option>
                        <option value="JPY" {{ old('currency', $tenant->currency ?? 'IDR') === 'JPY' ? 'selected' : '' }}>
                            JPY - Japanese Yen (¥)
                        </option>
                        <option value="SGD" {{ old('currency', $tenant->currency ?? 'IDR') === 'SGD' ? 'selected' : '' }}>
                            SGD - Singapore Dollar (S$)
                        </option>
                        <option value="MYR" {{ old('currency', $tenant->currency ?? 'IDR') === 'MYR' ? 'selected' : '' }}>
                            MYR - Malaysian Ringgit (RM)
                        </option>
                    </select>
                    @error('currency')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        This setting affects how currency is displayed throughout the application for your family.
                    </p>
                </div>

                <!-- Monthly Budget (Placeholder for future) -->
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 mr-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            <strong>Monthly Budget</strong> - Coming soon! Set a monthly spending limit for your family.
                        </span>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-gray-100 dark:border-gray-700">
                    <button type="submit"
                        class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
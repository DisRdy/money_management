<x-app-layout>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Personal Settings</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Manage your personal preferences and account settings.</p>
    </div>

    <div class="max-w-3xl space-y-6">
        <!-- Theme Selection Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                    </svg>
                    Theme Preference
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Choose your preferred appearance for the
                    application.</p>
            </div>

            <form method="POST" action="{{ route('settings.personal.theme') }}" class="p-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Light Theme -->
                    <label class="relative cursor-pointer">
                        <input type="radio" name="theme" value="light" class="peer sr-only" {{ $theme === 'light' ? 'checked' : '' }}>
                        <div
                            class="p-4 border-2 rounded-xl transition-all duration-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/30 border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 bg-white dark:bg-gray-700">
                            <div class="flex flex-col items-center text-center">
                                <div class="w-12 h-12 mb-3 rounded-full bg-yellow-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-800 dark:text-gray-100">Light</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">Bright and clean</span>
                            </div>
                        </div>
                        <div class="absolute top-2 right-2 hidden peer-checked:block">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </label>

                    <!-- Dark Theme -->
                    <label class="relative cursor-pointer">
                        <input type="radio" name="theme" value="dark" class="peer sr-only" {{ $theme === 'dark' ? 'checked' : '' }}>
                        <div
                            class="p-4 border-2 rounded-xl transition-all duration-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/30 border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 bg-white dark:bg-gray-700">
                            <div class="flex flex-col items-center text-center">
                                <div class="w-12 h-12 mb-3 rounded-full bg-gray-800 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-800 dark:text-gray-100">Dark</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">Easy on the eyes</span>
                            </div>
                        </div>
                        <div class="absolute top-2 right-2 hidden peer-checked:block">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </label>

                    <!-- System Theme -->
                    <label class="relative cursor-pointer">
                        <input type="radio" name="theme" value="system" class="peer sr-only" {{ $theme === 'system' ? 'checked' : '' }}>
                        <div
                            class="p-4 border-2 rounded-xl transition-all duration-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/30 border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 bg-white dark:bg-gray-700">
                            <div class="flex flex-col items-center text-center">
                                <div
                                    class="w-12 h-12 mb-3 rounded-full bg-gradient-to-r from-yellow-100 to-gray-800 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-800 dark:text-gray-100">System</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">Uses light mode</span>
                            </div>
                        </div>
                        <div class="absolute top-2 right-2 hidden peer-checked:block">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </label>
                </div>

                <!-- Note about system theme -->
                <p class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                    <strong>Note:</strong> "System" currently defaults to light mode for consistency.
                </p>

                @error('theme')
                    <p class="mt-3 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror

                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                        Save Theme
                    </button>
                </div>
            </form>
        </div>

        <!-- Change Password Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Change Password
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Update your password to keep your account
                    secure.</p>
            </div>

            <div class="p-6">
                @include('profile.partials.update-password-form')
            </div>
        </div>
    </div>
</x-app-layout>
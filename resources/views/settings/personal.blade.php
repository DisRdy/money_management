<x-app-layout>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Personal Settings</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Manage your personal preferences and account settings.</p>
    </div>

    <div class="max-w-3xl space-y-6">
        <!-- Profile Photo Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profile Photo
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Upload a photo to personalize your account.</p>
            </div>

            <div class="p-6">
                <div class="flex flex-col sm:flex-row items-center gap-6">
                    <!-- Current Avatar -->
                    <div class="flex-shrink-0">
                        @if($user->profile_photo_url)
                            <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"
                                class="w-24 h-24 rounded-full object-cover border-4 border-indigo-100 dark:border-indigo-900 shadow-md">
                        @else
                            <div
                                class="w-24 h-24 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-2xl shadow-md border-4 border-indigo-100 dark:border-indigo-900">
                                {{ $user->initials }}
                            </div>
                        @endif
                    </div>

                    <!-- Upload Form -->
                    <div class="flex-1 w-full">
                        <form method="POST" action="{{ route('settings.personal.profile-photo') }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="space-y-4">
                                <div>
                                    <label for="profile_photo"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Choose a new photo
                                    </label>
                                    <input type="file" name="profile_photo" id="profile_photo"
                                        accept=".jpg,.jpeg,.png,.webp" class="block w-full text-sm text-gray-500 dark:text-gray-400
                                            file:mr-4 file:py-2 file:px-4
                                            file:rounded-lg file:border-0
                                            file:text-sm file:font-semibold
                                            file:bg-indigo-50 file:text-indigo-700
                                            dark:file:bg-indigo-900/50 dark:file:text-indigo-300
                                            hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900
                                            file:cursor-pointer file:transition-colors">
                                </div>

                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Allowed formats: JPG, PNG, WebP. Maximum size: 2MB.
                                </p>

                                @error('profile_photo')
                                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror

                                <div class="flex flex-wrap gap-3">
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Upload Photo
                                    </button>
                                </div>
                            </div>
                        </form>

                        @if($user->profile_photo_url)
                            <form method="POST" action="{{ route('settings.personal.profile-photo.delete') }}"
                                class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700"
                                onsubmit="return confirm('Are you sure you want to remove your profile photo?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Remove Photo
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
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
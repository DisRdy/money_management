<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Family Member') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            {{-- Form Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    {{-- Form Header --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Add New Member</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Create a new account for a family member. They will be added to 
                            <span class="font-semibold text-indigo-600">{{ Auth::user()->tenant->name }}</span> family.
                        </p>
                    </div>

                    {{-- Form --}}
                    <form method="POST" action="{{ route('family.store') }}">
                        @csrf

                        {{-- Name Field --}}
                        <div class="mb-5">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name') }}"
                                   required
                                   autofocus
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email Field --}}
                        <div class="mb-5">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   value="{{ old('email') }}"
                                   required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @else
                                <p class="mt-1 text-xs text-gray-500">This email will be used for login.</p>
                            @enderror
                        </div>

                        {{-- Role Field --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Role <span class="text-red-500">*</span>
                            </label>
                            <div class="space-y-3">
                                {{-- Wife Option --}}
                                <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer transition-colors {{ old('role') === 'wife' ? 'border-purple-500 bg-purple-50' : 'border-gray-200 hover:border-purple-300' }}">
                                    <input type="radio" 
                                           name="role" 
                                           value="wife" 
                                           {{ old('role') === 'wife' ? 'checked' : '' }}
                                           class="mt-1 text-purple-600 focus:ring-purple-500">
                                    <div class="ml-3">
                                        <div class="flex items-center">
                                            <span class="text-sm font-semibold text-gray-900">💼 Wife</span>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-600">
                                            Can view family transactions and create transactions. Cannot manage categories.
                                        </p>
                                    </div>
                                </label>

                                {{-- Child Option --}}
                                <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer transition-colors {{ old('role') === 'child' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300' }}">
                                    <input type="radio" 
                                           name="role" 
                                           value="child" 
                                           {{ old('role') === 'child' ? 'checked' : '' }}
                                           class="mt-1 text-blue-600 focus:ring-blue-500">
                                    <div class="ml-3">
                                        <div class="flex items-center">
                                            <span class="text-sm font-semibold text-gray-900">🎓 Child</span>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-600">
                                            Can view and create personal transactions only. Limited access.
                                        </p>
                                    </div>
                                </label>
                            </div>
                            @error('role')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password Field --}}
                        <div class="mb-5">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   required
                                   minlength="8"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @else
                                <p class="mt-1 text-xs text-gray-500">Minimum 8 characters. This will be the login password for the new member.</p>
                            @enderror
                        </div>

                        {{-- Password Confirmation Field --}}
                        <div class="mb-6">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirm Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   required
                                   minlength="8"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-500">Re-enter the password to confirm.</p>
                        </div>

                        {{-- Info Alert --}}
                        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-blue-800">Important</h4>
                                    <p class="mt-1 text-xs text-blue-700">
                                        Make sure to remember or securely save the password you set. 
                                        You'll need to share it with the new family member so they can log in.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Form Actions --}}
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                            <a href="{{ route('family.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Add Member
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Additional Info --}}
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-blue-800">Important Notes</h4>
                        <ul class="mt-2 text-xs text-blue-700 list-disc list-inside space-y-1">
                            <li>New members will be added to your family ({{ Auth::user()->tenant->name }})</li>
                            <li>They will share the same transaction categories as your family</li>
                            <li>Each email can only be used once across the entire system</li>
                            <li>Make sure to save and share the password with the new member</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

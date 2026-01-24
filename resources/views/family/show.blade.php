<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Member Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Back Button --}}
            <div class="mb-6">
                <a href="{{ route('family.index') }}"
                    class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Family Members
                </a>
            </div>

            {{-- Member Profile Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="relative">
                    {{-- Cover --}}
                    <div
                        class="h-32 {{ $user->isOwner() ? 'bg-gradient-to-r from-yellow-400 to-orange-500' : ($user->isWife() ? 'bg-gradient-to-r from-purple-500 to-pink-500' : 'bg-gradient-to-r from-blue-500 to-cyan-500') }}">
                    </div>

                    {{-- Profile Content --}}
                    <div class="px-6 pb-6">
                        <div class="flex flex-col sm:flex-row items-center sm:items-end -mt-16 sm:-mt-12">
                            {{-- Avatar --}}
                            <div class="relative mb-4 sm:mb-0">
                                <div
                                    class="w-24 h-24 sm:w-32 sm:h-32 rounded-full border-4 border-white shadow-lg {{ $user->isOwner() ? 'bg-gradient-to-br from-yellow-400 to-orange-500' : ($user->isWife() ? 'bg-gradient-to-br from-purple-500 to-pink-500' : 'bg-gradient-to-br from-blue-500 to-cyan-500') }} flex items-center justify-center text-white font-bold text-3xl sm:text-4xl">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            </div>

                            {{-- Name and Role --}}
                            <div class="sm:ml-6 text-center sm:text-left flex-1">
                                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                                    {{ $user->name }}
                                    @if($user->id === Auth::id())
                                        <span class="text-lg text-indigo-600">(You)</span>
                                    @endif
                                </h1>
                                <div class="mt-2">
                                    @if($user->isOwner())
                                        <span
                                            class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-gradient-to-r from-yellow-400 to-orange-500 text-white">
                                            Owner
                                        </span>
                                    @elseif($user->isWife())
                                        <span
                                            class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-gradient-to-r from-purple-500 to-pink-500 text-white">
                                            Wife
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-gradient-to-r from-blue-500 to-cyan-500 text-white">
                                            Child
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Member Information --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Member Information</h3>

                    <div class="space-y-4">
                        {{-- Full Name --}}
                        <div class="flex flex-col sm:flex-row sm:items-center py-3 border-b border-gray-200">
                            <div class="sm:w-1/3">
                                <span class="text-sm font-medium text-gray-500">Full Name</span>
                            </div>
                            <div class="sm:w-2/3 mt-1 sm:mt-0">
                                <span class="text-sm text-gray-900 font-semibold">{{ $user->name }}</span>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="flex flex-col sm:flex-row sm:items-center py-3 border-b border-gray-200">
                            <div class="sm:w-1/3">
                                <span class="text-sm font-medium text-gray-500">Email Address</span>
                            </div>
                            <div class="sm:w-2/3 mt-1 sm:mt-0">
                                <span class="text-sm text-gray-900">{{ $user->email }}</span>
                            </div>
                        </div>

                        {{-- Role --}}
                        <div class="flex flex-col sm:flex-row sm:items-center py-3 border-b border-gray-200">
                            <div class="sm:w-1/3">
                                <span class="text-sm font-medium text-gray-500">Role</span>
                            </div>
                            <div class="sm:w-2/3 mt-1 sm:mt-0">
                                <span class="text-sm text-gray-900 font-medium capitalize">{{ $user->role }}</span>
                            </div>
                        </div>

                        {{-- Family --}}
                        <div class="flex flex-col sm:flex-row sm:items-center py-3 border-b border-gray-200">
                            <div class="sm:w-1/3">
                                <span class="text-sm font-medium text-gray-500">Family</span>
                            </div>
                            <div class="sm:w-2/3 mt-1 sm:mt-0">
                                <span class="text-sm text-gray-900 font-semibold">{{ $user->tenant->name }}</span>
                            </div>
                        </div>

                        {{-- Member Since --}}
                        <div class="flex flex-col sm:flex-row sm:items-center py-3">
                            <div class="sm:w-1/3">
                                <span class="text-sm font-medium text-gray-500">Member Since</span>
                            </div>
                            <div class="sm:w-2/3 mt-1 sm:mt-0">
                                <span class="text-sm text-gray-900">{{ $user->created_at->format('d F Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            @if(!$user->isOwner())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                        <div class="flex flex-wrap gap-3">
                            {{-- Edit Button --}}
                            <button type="button"
                                onclick="openEditModal('family', {{ $user->id }}, {{ json_encode(['id' => $user->id, 'name' => $user->name, 'email' => $user->email, 'role' => $user->role]) }})"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Member
                            </button>

                            {{-- Delete Button --}}
                            <form method="POST" action="{{ route('family.destroy', $user) }}"
                                data-confirm-delete="this family member ({{ $user->name }})" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete Member
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                {{-- Cannot Edit Owner --}}
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-yellow-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-yellow-800">Owner Account Protection</h4>
                            <p class="mt-1 text-xs text-yellow-700">
                                The Owner account cannot be edited or deleted through this interface for security reasons.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
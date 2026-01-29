@php
    $user = Auth::user();
    $currentRoute = Route::currentRouteName();
@endphp

<aside id="sidebar"
    class="fixed left-0 top-0 h-full w-64 bg-white border-r border-gray-200 shadow-sm transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-20">
    <div class="flex flex-col h-full">
        <!-- Logo & Brand -->
        <div class="px-6 py-5 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-indigo-600">Yuk Nabung</h1>
            <p class="text-xs text-gray-500 mt-1">Watch your money</p>
        </div>

        <!-- User Info Card -->
        <div class="px-4 py-4 border-b border-gray-100 bg-gradient-to-br from-indigo-50 to-white">
            <div class="flex items-center space-x-3">
                <div
                    class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-semibold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $user->name }}</p>
                    <p class="text-xs text-indigo-600 font-medium">
                        @if($user->role === 'owner')
                            Owner
                        @elseif($user->role === 'wife')
                            Wife
                        @else
                            Child
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-3 py-4 overflow-y-auto">
            <ul class="space-y-1">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center px-4 py-3 rounded-lg transition-colors duration-150 {{ $currentRoute === 'dashboard' ? 'bg-indigo-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="font-medium">Dashboard</span>
                    </a>
                </li>

                <!-- Transactions -->
                <li>
                    <a href="{{ route('transactions.index') }}"
                        class="flex items-center px-4 py-3 rounded-lg transition-colors duration-150 {{ Str::startsWith($currentRoute, 'transactions') ? 'bg-indigo-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-medium">Transactions</span>
                    </a>
                </li>

                <!-- Categories (Owner Only) -->
                @if($user->role === 'owner')
                    <li>
                        <a href="{{ route('categories.index') }}"
                            class="flex items-center px-4 py-3 rounded-lg transition-colors duration-150 {{ Str::startsWith($currentRoute, 'categories') ? 'bg-indigo-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            <span class="font-medium">Categories</span>
                        </a>
                    </li>

                    <!-- Family Members (Owner Only) -->
                    <li>
                        <a href="{{ route('family.index') }}"
                            class="flex items-center px-4 py-3 rounded-lg transition-colors duration-150 {{ Str::startsWith($currentRoute, 'family') ? 'bg-indigo-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="font-medium">Family Members</span>
                        </a>
                    </li>

                    <!-- Audit Logs (Owner Only) -->
                    <li>
                        <a href="{{ route('audit-logs.index') }}"
                            class="flex items-center px-4 py-3 rounded-lg transition-colors duration-150 {{ Str::startsWith($currentRoute, 'audit-logs') ? 'bg-indigo-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="font-medium">Audit Logs</span>
                        </a>
                    </li>
                @endif

                <!-- Divider -->
                <li class="pt-4 pb-2">
                    <div class="border-t border-gray-200"></div>
                </li>

                <!-- Reports -->
                <li>
                    <a href="{{ route('reports.index') }}"
                        class="flex items-center px-4 py-3 rounded-lg transition-colors duration-150 {{ Str::startsWith($currentRoute, 'reports') ? 'bg-indigo-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span class="font-medium">Reports</span>
                    </a>
                </li>

                <!-- Settings (Coming Soon) -->
                <li>
                    <div class="flex items-center px-4 py-3 rounded-lg text-gray-400 cursor-not-allowed opacity-60">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="font-medium">Settings</span>
                        <span class="ml-auto text-xs bg-gray-200 px-2 py-1 rounded">Soon</span>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Logout Button -->
        <div class="px-3 py-4 border-t border-gray-200">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition-colors duration-150">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="font-medium">Logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>
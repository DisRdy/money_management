<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Yuk Nabung') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <x-sidebar />

        <!-- Main Content Area -->
        <div class="flex-1 md:ml-64">
            <!-- Top Navigation Bar (Mobile) -->
            <div class="md:hidden bg-white border-b border-gray-200 sticky top-0 z-10">
                <div class="px-4 py-3 flex items-center justify-between">
                    <h1 class="text-xl font-bold text-gray-800">{{ config('app.name', 'Yok Nabung') }}</h1>
                    <button id="mobile-menu-button" class="text-gray-600 hover:text-gray-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Page Content -->
            <main class="p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    {{-- Toast Notifications --}}
    <x-toast />

    {{-- Edit Modal --}}
    <x-edit-modal />

    {{-- Delete Confirmation Modal --}}
    <x-delete-modal />

    <!-- Form Templates JavaScript -->
    <script src="{{ asset('js/modal-edit-forms.js') }}"></script>

    <!-- Mobile Menu Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const sidebar = document.getElementById('sidebar');

            if (mobileMenuButton && sidebar) {
                mobileMenuButton.addEventListener('click', function () {
                    sidebar.classList.toggle('-translate-x-full');
                });

                // Close sidebar when clicking outside
                document.addEventListener('click', function (event) {
                    const isClickInsideSidebar = sidebar.contains(event.target);
                    const isClickOnButton = mobileMenuButton.contains(event.target);

                    if (!isClickInsideSidebar && !isClickOnButton && window.innerWidth < 768) {
                        sidebar.classList.add('-translate-x-full');
                    }
                });
            }
        });
    </script>
</body>

</html>
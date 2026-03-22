<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - BarliTor Shop')</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="bg-[#111111] text-gray-200 font-sans antialiased min-h-screen">
    
    <!-- Sidebar -->
    <!-- Mobile backdrop -->
    <div id="admin-sidebar-backdrop" class="fixed inset-0 bg-black/60 z-40 hidden md:hidden"></div>

    <aside id="admin-sidebar"
           class="fixed inset-y-0 left-0 w-64 bg-[#1a1a1a] border-r border-gray-800 flex flex-col flex-shrink-0 h-screen overflow-y-auto z-50
                  transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-out">
        <div class="h-16 flex items-center px-6 border-b border-gray-800">
            <a href="{{ route('home') }}" class="text-white font-bold text-xl tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-store text-orange-500"></i> BarliTor <span class="text-xs text-gray-500 font-normal ml-1">ADMIN</span>
            </a>
        </div>
        
        <div class="px-4 py-6 flex-grow">
            <nav class="space-y-1">
                <p class="px-2 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Management</p>
                
                <a href="{{ route('items.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-300 hover:bg-gray-800 hover:text-white transition-colors group">
                    <i class="fa-solid fa-boxes-stacked mr-3 w-5 text-center text-gray-400 group-hover:text-white"></i>
                    Items Catalog
                </a>
                
                <a href="{{ route('admin.users') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.users*') ? 'bg-orange-500/10 text-orange-500' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition-colors group">
                    <i class="fa-solid fa-users mr-3 w-5 text-center {{ request()->routeIs('admin.users*') ? 'text-orange-500' : 'text-gray-400 group-hover:text-white' }}"></i>
                    Users
                </a>
                
                <a href="{{ route('admin.suppliers.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.suppliers*') ? 'bg-orange-500/10 text-orange-500' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition-colors group">
                    <i class="fa-solid fa-truck-field mr-3 w-5 text-center {{ request()->routeIs('admin.suppliers*') ? 'text-orange-500' : 'text-gray-400 group-hover:text-white' }}"></i>
                    Suppliers
                </a>
                
                <a href="{{ route('admin.expenses.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.expenses*') ? 'bg-orange-500/10 text-orange-500' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition-colors group">
                    <i class="fa-solid fa-file-invoice-dollar mr-3 w-5 text-center {{ request()->routeIs('admin.expenses*') ? 'text-orange-500' : 'text-gray-400 group-hover:text-white' }}"></i>
                    Expenses
                </a>

                <a href="{{ route('admin.orders.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.orders*') ? 'bg-orange-500/10 text-orange-500' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition-colors group">
                    <i class="fa-solid fa-receipt mr-3 w-5 text-center {{ request()->routeIs('admin.orders*') ? 'text-orange-500' : 'text-gray-400 group-hover:text-white' }}"></i>
                    Orders
                </a>
                
                <p class="px-2 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 mt-6">Analytics</p>
                
                <a href="{{ route('admin.reports') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.reports*') ? 'bg-orange-500/10 text-orange-500' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition-colors group">
                    <i class="fa-solid fa-chart-line mr-3 w-5 text-center {{ request()->routeIs('admin.reports*') ? 'text-orange-500' : 'text-gray-400 group-hover:text-white' }}"></i>
                    Reports
                </a>
            </nav>
        </div>
        
        <div class="px-4 py-4 border-t border-gray-800">
            <div class="flex items-center">
                @php
                    $avatar = auth()->user()->avatar ? asset(auth()->user()->avatar) : 'https://bootdey.com/img/Content/avatar/avatar1.png';
                @endphp
                <img class="h-8 w-8 rounded-full border border-gray-600 object-cover" src="{{ $avatar }}" alt="">
                <div class="ml-3">
                    <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                    <a href="{{ route('logout') }}" class="text-xs font-medium text-red-400 hover:text-red-300 transition-colors">Sign out</a>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="min-h-screen md:pl-64 flex flex-col min-w-0">
        <!-- Top Nav (Mobile & Additional Actions) -->
        <header class="bg-[#1a1a1a] border-b border-gray-800 h-16 flex items-center justify-between px-4 sm:px-6 z-40 sticky top-0">
            <div class="flex items-center md:hidden">
                <button type="button" class="text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-orange-500" id="mobile-sidebar-toggle">
                    <span class="sr-only">Open sidebar</span>
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <span class="ml-3 font-bold text-white tracking-tight">BarliTor Shop</span>
            </div>
            
            <div class="hidden md:flex flex-1 justify-between px-4">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-white">@yield('title_header', 'Dashboard')</h1>
                </div>
                <div class="flex items-center gap-4 text-sm">
                    <a href="{{ route('home') }}" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fa-solid fa-globe mr-1"></i> View Storefront
                    </a>
                </div>
            </div>
            
            <!-- Mobile Storefront Link -->
            <a href="{{ route('home') }}" class="md:hidden text-gray-400 hover:text-white flex-shrink-0">
                <i class="fa-solid fa-globe text-xl"></i>
            </a>
        </header>
        
        <!-- Alerts -->
        <div class="px-4 sm:px-6 lg:px-8 mt-6">
            @if(session('success'))
                <div class="bg-green-900/50 border border-green-500 text-green-200 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-900/50 border border-red-500 text-red-200 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
        </div>

        <main class="flex-1 focus:outline-none py-6">
            <div class="px-4 sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var toggleBtn = document.getElementById('mobile-sidebar-toggle');
            var sidebar = document.getElementById('admin-sidebar');
            var backdrop = document.getElementById('admin-sidebar-backdrop');

            function openSidebar() {
                if (!sidebar || !backdrop) return;
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeSidebar() {
                if (!sidebar || !backdrop) return;
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function () {
                    if (!sidebar) return;
                    var isClosed = sidebar.classList.contains('-translate-x-full');
                    if (isClosed) openSidebar();
                    else closeSidebar();
                });
            }

            if (backdrop) {
                backdrop.addEventListener('click', closeSidebar);
            }

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeSidebar();
            });

            // Ensure sidebar is reset when resizing to desktop.
            window.addEventListener('resize', function () {
                if (window.innerWidth >= 768) {
                    if (sidebar) sidebar.classList.remove('-translate-x-full');
                    if (backdrop) backdrop.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                } else {
                    if (sidebar) sidebar.classList.add('-translate-x-full');
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>

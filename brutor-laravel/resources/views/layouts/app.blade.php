<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BruTor Shop')</title>
    
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
<body class="bg-[#111111] text-gray-200 font-sans antialiased min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="bg-[#1a1a1a] border-b border-gray-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo & Desktop Menu -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center gap-2 text-white font-bold text-xl tracking-tight">
                        <i class="fa-solid fa-store text-orange-500"></i> BruTor Shop
                    </a>
                    
                    <div class="hidden md:ml-8 md:flex md:space-x-4">
                        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} px-3 py-2 rounded-md text-sm font-medium transition-colors">Home</a>
                        <a href="{{ route('items.index') }}" class="{{ request()->routeIs('items.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} px-3 py-2 rounded-md text-sm font-medium transition-colors">Items</a>
                        
                        @auth
                            @if(auth()->user()->isAdmin())
                                <div class="relative group">
                                    <button class="text-gray-300 group-hover:bg-gray-700 group-hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center gap-2">
                                        <i class="fa-solid fa-user-shield"></i> Admin <i class="fa-solid fa-chevron-down text-xs"></i>
                                    </button>
                                    <div class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-[#262626] ring-1 ring-black ring-opacity-5 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                        <div class="py-1" role="menu" aria-orientation="vertical">
                                            <a href="{{ route('items.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">Manage Items</a>
                                            <a href="{{ route('admin.users') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">Users</a>
                                            <a href="{{ route('admin.suppliers.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">Suppliers</a>
                                            <a href="{{ route('admin.expenses.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">Expenses</a>
                                            <a href="{{ route('admin.reports') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">Reports</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Right Side (Search, Auth, Cart) -->
                <div class="hidden md:flex items-center space-x-4">
                    <form action="{{ route('items.index') }}" method="GET" class="relative">
                        <input type="search" name="search" placeholder="Search..." value="{{ request('search') }}"
                            class="bg-gray-800 border border-gray-700 text-gray-300 text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full pl-3 py-1.5 pr-10">
                        <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <i class="fa-solid fa-search text-gray-400 hover:text-orange-500"></i>
                        </button>
                    </form>

                    @guest
                        <a href="{{ route('login') }}" class="text-gray-300 hover:text-white text-sm font-medium transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-1.5 rounded-md text-sm font-medium transition-colors">Register</a>
                    @else
                        <!-- User Dropdown -->
                        <div class="relative group">
                            @php
                                $avatarPath = auth()->user()->avatar
                                    ? asset(auth()->user()->avatar)
                                    : 'https://bootdey.com/img/Content/avatar/avatar1.png';
                            @endphp
                            <button class="flex items-center gap-2 text-gray-300 hover:text-white text-sm font-medium focus:outline-none">
                                <img src="{{ $avatarPath }}" alt="avatar" class="rounded-full w-8 h-8 object-cover border-2 border-gray-700">
                                <span>{{ auth()->user()->name }}</span>
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-[#262626] ring-1 ring-black ring-opacity-5 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                <div class="py-1">
                                    <a href="{{ route('profile.show', auth()->id()) }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white"><i class="fa-solid fa-user w-5"></i> Profile</a>
                                    @if(!auth()->user()->isAdmin())
                                        <a href="{{ route('orders.mine') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white"><i class="fa-solid fa-box w-5"></i> My Orders</a>
                                    @endif
                                    <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm text-red-400 hover:bg-gray-700 hover:text-white"><i class="fa-solid fa-right-from-bracket w-5"></i> Logout</a>
                                </div>
                            </div>
                        </div>
                    @endguest

                    <!-- Cart Icon -->
                    <a href="{{ auth()->check() ? route('cart.index') : route('login') }}" class="relative text-gray-300 hover:text-orange-500 transition-colors p-2">
                        <i class="fa-solid fa-cart-shopping text-lg"></i>
                        @php
                            $cartCount = 0;
                            $cart = session('cart', ['products' => [], 'tools' => []]);
                            if (!empty($cart['products'])) {
                                $cartCount += array_sum(array_column($cart['products'], 'quantity'));
                            }
                            if (!empty($cart['tools'])) {
                                $cartCount += array_sum(array_column($cart['tools'], 'quantity'));
                            }
                        @endphp
                        @if($cartCount > 0)
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-orange-500 rounded-full">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="flex items-center md:hidden">
                    <button type="button" id="mobile-menu-btn" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-orange-500" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden hidden bg-[#1a1a1a] border-t border-gray-800" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('home') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Home</a>
                <a href="{{ route('items.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('items.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Items</a>
                
                @auth
                    @if(auth()->user()->isAdmin())
                        <div class="border-t border-gray-700 mt-2 pt-2">
                            <span class="block px-3 py-1 text-sm text-gray-400 font-semibold uppercase">Admin</span>
                            <a href="{{ route('items.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Manage Items</a>
                            <a href="{{ route('admin.users') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Users</a>
                            <a href="{{ route('admin.suppliers.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Suppliers</a>
                            <a href="{{ route('admin.expenses.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Expenses</a>
                            <a href="{{ route('admin.reports') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Reports</a>
                        </div>
                    @endif
                @endauth
            </div>
            
            <div class="pt-4 pb-3 border-t border-gray-800">
                <div class="px-5 flex items-center justify-between">
                    @auth
                        <div class="flex items-center">
                            @php
                                $avatarPathM = auth()->user()->avatar ? asset(auth()->user()->avatar) : 'https://bootdey.com/img/Content/avatar/avatar1.png';
                            @endphp
                            <div class="flex-shrink-0">
                                <img class="h-10 w-10 rounded-full border border-gray-600" src="{{ $avatarPathM }}" alt="">
                            </div>
                            <div class="ml-3">
                                <div class="text-base font-medium text-white">{{ auth()->user()->name }}</div>
                            </div>
                        </div>
                    @else
                        <div class="flex space-x-3">
                            <a href="{{ route('login') }}" class="text-gray-300 hover:text-white py-2">Login</a>
                            <a href="{{ route('register') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-2 rounded-md">Register</a>
                        </div>
                    @endauth
                    
                    <!-- Mobile Cart -->
                    <a href="{{ auth()->check() ? route('cart.index') : route('login') }}" class="ml-auto flex-shrink-0 p-1 text-gray-400 hover:text-orange-500 relative">
                        <span class="sr-only">View cart</span>
                        <i class="fa-solid fa-cart-shopping text-xl"></i>
                        @if(isset($cartCount) && $cartCount > 0)
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-orange-500 rounded-full">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                </div>
                
                @auth
                    <div class="mt-3 px-2 space-y-1">
                        <a href="{{ route('profile.show', auth()->id()) }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700">Profile</a>
                        @if(!auth()->user()->isAdmin())
                            <a href="{{ route('orders.mine') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700">My Orders</a>
                        @endif
                        <a href="{{ route('logout') }}" class="block px-3 py-2 rounded-md text-base font-medium text-red-400 hover:text-white hover:bg-gray-700">Logout</a>
                    </div>
                @endauth
                
                <form action="{{ route('items.index') }}" method="GET" class="px-5 mt-4 mb-2">
                    <div class="relative">
                        <input type="search" name="search" placeholder="Search..." value="{{ request('search') }}"
                            class="bg-gray-800 border border-gray-700 text-gray-300 text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full pl-3 py-2 pr-10">
                        <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <i class="fa-solid fa-search text-gray-400"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
        <!-- Alerts -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
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
            @if(session('info'))
                <div class="bg-blue-900/50 border border-blue-500 text-blue-200 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('info') }}</span>
                </div>
            @endif
            @if($errors->any())
                <div class="bg-red-900/50 border border-red-500 text-red-200 px-4 py-3 rounded relative mb-4" role="alert">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#1a1a1a] border-t border-gray-800 mt-12 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <a href="{{ route('home') }}" class="text-white font-bold text-lg flex items-center gap-2">
                    <i class="fa-solid fa-store text-orange-500"></i> BruTor Shop
                </a>
                <p class="text-gray-500 text-sm mt-1">Quality motorcycle parts & tools</p>
            </div>
            <div class="text-gray-500 text-sm">
                &copy; {{ date('Y') }} BruTor Shop. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Mobile Menu Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if(mobileBtn && mobileMenu) {
                mobileBtn.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>

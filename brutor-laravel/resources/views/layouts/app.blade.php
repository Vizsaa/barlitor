<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BruTor Shop')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { min-height: 100vh; display: flex; flex-direction: column; }
        main { flex: 1; }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}"><i class="fa-solid fa-store"></i> BruTor Shop</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('items.*') ? 'active' : '' }}" href="{{ route('items.index') }}">Items</a>
                    </li>

                    @auth
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-user-shield"></i> Admin Menu
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('items.index') }}">Manage Items</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.users') }}">Users</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.suppliers.index') }}">Suppliers</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.expenses.index') }}">Expenses</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.reports') }}">Reports</a></li>
                                </ul>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                @php
                                    $avatarPath = auth()->user()->avatar
                                        ? asset(auth()->user()->avatar)
                                        : 'https://bootdey.com/img/Content/avatar/avatar1.png';
                                @endphp
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                    <img src="{{ $avatarPath }}" alt="avatar" class="rounded-circle me-2" width="32" height="32" style="object-fit:cover;">
                                    {{ auth()->user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('profile.show', auth()->id()) }}"><i class="fa-solid fa-user"></i> Profile</a></li>
                                    <li><a class="dropdown-item" href="{{ route('orders.mine') }}"><i class="fa-solid fa-box"></i> My Orders</a></li>
                                </ul>
                            </li>
                        @endif
                    @endauth
                </ul>

                <form class="d-flex me-3" action="{{ route('items.index') }}" method="GET">
                    <input class="form-control me-2" type="search" placeholder="Search" name="search" value="{{ request('search') }}">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>

                <ul class="navbar-nav mb-2 mb-lg-0">
                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}"><i class="fa-solid fa-right-to-bracket"></i> Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
                    @endguest

                    <li class="nav-item position-relative">
                        <a class="nav-link" href="{{ auth()->check() ? route('cart.index') : route('login') }}">
                            <i class="fa-solid fa-cart-shopping"></i>
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
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <main>
        <div class="container mt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>

        @yield('content')
    </main>

    <footer class="bg-dark text-light mt-5 py-4">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div>
                <a href="{{ route('home') }}" class="text-decoration-none text-light fw-bold">
                    <i class="fa-solid fa-store"></i> BruTor Shop
                </a>
            </div>
            <div class="mt-3 mt-md-0 text-secondary small">
                &copy; {{ date('Y') }} BruTor Shop. All rights reserved.
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>

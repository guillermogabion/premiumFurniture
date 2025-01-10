<div class="sidebar  @if(auth()->user()->role == 'client') d-none @endif" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="index.html" class="logo">
                <img
                    src="{{ asset('img/kaiadmin/logos.png') }}" alt="Image Preview" style="display: block; width: auto; height: 50px; border-radius: 50%; object-fit: cover; border: 1px solid #ddd; padding: 5px; cursor: pointer;"
                    alt="navbar brand"
                    class="navbar-brand"
                    height="10" />
                <span class="text-sm text-nowrap text-white fs-6 logo-text navbar-brand">PREMIER FURNITURE PH</span>
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <!-- Dashboard -->
                <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="icon-grid menu-icon"></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>
                @if ($profile && $profile->role == 'admin')

                <li class="nav-item {{ request()->routeIs('vendor') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('vendor') }}">
                        <i class="fas fa-users"></i>
                        <span class="menu-title">Vendor List</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('client') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('client') }}">
                        <i class="fas fa-user-friends"></i>
                        <span class="menu-title">Customer List</span>
                    </a>
                </li>

                @else
                <li class="nav-item {{ request()->routeIs('products') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('products') }}">
                        <i class="fas fa-users"></i>
                        <span class="menu-title">Product List</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('orders') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('orders') }}">
                        <i class="fas fa-users"></i>
                        <span class="menu-title">Orders List</span>
                    </a>
                </li>

                @endif

                @if ($profile && $profile->role == 'admin')

                <div class="ml-4">
                    <span>
                        Maintenance
                    </span>
                </div>

                <li class="nav-item {{ request()->routeIs('shop_type') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('shop_type') }}">
                        <i class="fas fa-store"></i>
                        <span class="menu-title">Shop Type List</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('category') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('category') }}">
                        <i class="fas fa-th-list"></i>
                        <span class="menu-title">Category List</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('users') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('users') }}">
                        <i class="fas fa-users-cog"></i>
                        <span class="menu-title">User List</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('users') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('users') }}">
                        <i class="fas fa-cog"></i>
                        <span class="menu-title">Settings</span>
                    </a>
                </li>
                @else
                @endif
            </ul>
        </div>
    </div>
</div>
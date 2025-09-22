<!DOCTYPE html>
<html lang="{{ $currentLanguage ?? 'en' }}" dir="{{ in_array($currentLanguage ?? 'en', ['ar']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'XpertBid Admin')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Woodmart Theme Variables */
        :root {
            --primary-color: #43ACE9;
            --secondary-color: #606060;
            --third-color: #000000;
            --light-bg: #f8f9fa;
            --white: #ffffff;
            --border-color: #e9ecef;
            --text-dark: #2c3e50;
            --text-light: #6c757d;
            --shadow: 0 2px 10px rgba(0,0,0,0.08);
            --shadow-hover: 0 8px 25px rgba(0,0,0,0.15);
            --border-radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Global Styles */
        * {
            font-family: 'Inter', 'Poppins', sans-serif;
        }

        body {
            background-color: var(--light-bg);
            color: var(--text-dark);
            line-height: 1.6;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: linear-gradient(180deg, var(--white) 0%, #f8f9fa 100%);
            border-right: 1px solid var(--border-color);
            z-index: 1000;
            transition: var(--transition);
            overflow-y: auto;
            box-shadow: var(--shadow);
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar .nav-link {
            color: var(--text-light);
            padding: 14px 24px;
            border-radius: var(--border-radius);
            margin: 6px 16px;
            transition: var(--transition);
            text-decoration: none;
            display: flex;
            align-items: center;
            font-weight: 500;
            font-size: 14px;
            position: relative;
        }

        .sidebar .nav-link:hover {
            color: var(--primary-color);
            background: rgba(67, 172, 233, 0.08);
            transform: translateX(4px);
        }

        .sidebar .nav-link.active {
            color: var(--primary-color);
            background: rgba(67, 172, 233, 0.12);
            font-weight: 600;
        }

        .sidebar .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 20px;
            background: var(--primary-color);
            border-radius: 0 4px 4px 0;
        }

        .sidebar .nav-link i {
            margin-right: 16px;
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        /* Dropdown styling */
        .sidebar .nav-link[data-bs-toggle="collapse"] {
            position: relative;
        }

        .sidebar .nav-link[data-bs-toggle="collapse"] .fa-chevron-down {
            transition: var(--transition);
            position: absolute;
            right: 1rem;
            font-size: 12px;
        }

        .sidebar .nav-link[data-bs-toggle="collapse"][aria-expanded="true"] .fa-chevron-down {
            transform: rotate(180deg);
        }

        .sidebar .collapse .nav-link {
            padding-left: 2.5rem;
            font-size: 13px;
            margin: 4px 16px;
            font-weight: 400;
        }

        .sidebar .collapse .nav-link:hover {
            background-color: rgba(67, 172, 233, 0.06);
            color: var(--primary-color) !important;
        }

        .sidebar.collapsed .nav-link span {
            display: none;
        }

        .sidebar.collapsed .nav-link i {
            margin-right: 0;
        }

        .sidebar .p-4 h4 {
            color: var(--text-dark);
            font-weight: 700;
            font-size: 20px;
            margin-bottom: 0;
        }

        .sidebar .p-4 h4 i {
            color: var(--primary-color);
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            background-color: var(--light-bg);
            transition: var(--transition);
        }

        .main-content.expanded {
            margin-left: 80px;
        }

        /* Top Navbar */
        .top-navbar {
            background: var(--white);
            box-shadow: var(--shadow);
            padding: 20px 32px;
            margin-bottom: 32px;
            border-bottom: 1px solid var(--border-color);
        }

        .top-navbar h1 {
            color: var(--text-dark);
            font-weight: 600;
            font-size: 24px;
            margin-bottom: 0;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            margin-bottom: 24px;
            overflow: hidden;
        }

        .card-header {
            background: var(--white);
            border-bottom: 1px solid var(--border-color);
            padding: 20px 24px;
            font-weight: 600;
            color: var(--text-dark);
        }

        .card-body {
            padding: 24px;
        }

        /* Stat Cards */
        .stat-card {
            background: linear-gradient(135deg, var(--primary-color) 0%, #5ba3d4 100%);
            border-radius: var(--border-radius);
            padding: 28px;
            color: var(--white);
            margin-bottom: 24px;
            box-shadow: var(--shadow);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
        }

        .stat-card:hover::before {
            transform: scale(1.2);
        }

        .stat-card h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .stat-card h6 {
            opacity: 0.9;
            margin-bottom: 0;
            font-weight: 500;
            position: relative;
            z-index: 1;
        }

        /* Buttons */
        .btn-primary {
            background: var(--primary-color);
            border: none;
            border-radius: var(--border-radius);
            padding: 12px 24px;
            font-weight: 500;
            transition: var(--transition);
            box-shadow: 0 2px 8px rgba(67, 172, 233, 0.3);
        }

        .btn-primary:hover {
            background: #3a9bd1;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(67, 172, 233, 0.4);
        }

        .btn-secondary {
            background: var(--secondary-color);
            border: none;
            border-radius: var(--border-radius);
            padding: 12px 24px;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-secondary:hover {
            background: #525252;
            transform: translateY(-2px);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: var(--border-radius);
            padding: 10px 20px;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        /* Tables */
        .table {
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .table thead th {
            background: var(--primary-color);
            color: var(--white);
            border: none;
            padding: 16px;
            font-weight: 600;
            font-size: 14px;
        }

        .table tbody td {
            padding: 16px;
            vertical-align: middle;
            border-color: var(--border-color);
        }

        .table tbody tr:hover {
            background-color: rgba(67, 172, 233, 0.04);
        }

        /* Forms */
        .form-control {
            border-radius: var(--border-radius);
            border: 2px solid var(--border-color);
            padding: 12px 16px;
            transition: var(--transition);
            font-size: 14px;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(67, 172, 233, 0.15);
        }

        .form-label {
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        /* Badges */
        .badge {
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge.bg-success {
            background: #28a745 !important;
        }

        .badge.bg-warning {
            background: #ffc107 !important;
            color: var(--text-dark);
        }

        .badge.bg-danger {
            background: #dc3545 !important;
        }

        /* Sidebar Toggle */
        .sidebar-toggle {
            position: fixed;
            top: 24px;
            left: 24px;
            z-index: 1001;
            background: var(--primary-color);
            color: var(--white);
            border: none;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .sidebar-toggle:hover {
            transform: scale(1.1);
            box-shadow: var(--shadow-hover);
        }

        /* Dropdowns */
        .dropdown-menu {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-hover);
            padding: 8px 0;
        }

        .dropdown-item {
            padding: 10px 20px;
            transition: var(--transition);
            font-size: 14px;
        }

        .dropdown-item:hover {
            background-color: rgba(67, 172, 233, 0.08);
            color: var(--primary-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }

            .top-navbar {
                padding: 16px 20px;
            }

            .card-body {
                padding: 20px;
            }
        }

        /* Custom Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--secondary-color);
        }

        /* Success Messages */
        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            border: 1px solid rgba(40, 167, 69, 0.2);
            color: #155724;
            border-radius: var(--border-radius);
        }

        /* Error Messages */
        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.2);
            color: #721c24;
            border-radius: var(--border-radius);
        }

        /* Loading States */
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Image Thumbnails */
        .img-thumbnail {
            border-radius: var(--border-radius);
            border: 2px solid var(--border-color);
        }

        /* Section Headers */
        .section-header {
            background: var(--white);
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 24px;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }

        .section-header h5 {
            color: var(--text-dark);
            font-weight: 600;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <!-- Sidebar Toggle Button -->
    <button class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="p-4">
            <h4 class="text-white mb-4">
                <i class="fas fa-gavel me-2"></i>
                <span class="sidebar-text">XpertBid</span>
            </h4>
        </div>
        
        <nav class="nav flex-column">
            <!-- Dashboard -->
            <a class="nav-link {{ request()->is('admin') ? 'active' : '' }}" href="/admin">
                <i class="fas fa-tachometer-alt"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>
            
            <!-- Products Dropdown -->
            <div class="nav-item">
                <a class="nav-link {{ request()->is('admin/products*') || request()->is('admin/brands*') || request()->is('admin/tags*') || request()->is('admin/categories*') ? 'active' : '' }}" 
                   data-bs-toggle="collapse" 
                   href="#productsCollapse" 
                   role="button" 
                   aria-expanded="false" 
                   aria-controls="productsCollapse">
                    <i class="fas fa-box"></i>
                    <span class="sidebar-text">Products</span>
                    <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="productsCollapse">
                    <div class="nav flex-column ms-3">
                        <a class="nav-link {{ request()->is('admin/products*') ? 'active' : '' }}" href="/admin/products">
                            <i class="fas fa-box"></i>
                            <span class="sidebar-text">All Products</span>
                        </a>
                        <a class="nav-link {{ request()->is('admin/brands*') ? 'active' : '' }}" href="/admin/brands">
                            <i class="fas fa-star"></i>
                            <span class="sidebar-text">Brands</span>
                        </a>
                        <a class="nav-link {{ request()->is('admin/tags*') ? 'active' : '' }}" href="/admin/tags">
                            <i class="fas fa-tags"></i>
                            <span class="sidebar-text">Tags</span>
                        </a>
                        <a class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}" href="/admin/categories">
                            <i class="fas fa-layer-group"></i>
                            <span class="sidebar-text">Categories</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Properties -->
            <a class="nav-link {{ request()->is('admin/properties*') ? 'active' : '' }}" href="/admin/properties">
                <i class="fas fa-home"></i>
                <span class="sidebar-text">Properties</span>
            </a>
            
            <!-- Vehicles -->
            <a class="nav-link {{ request()->is('admin/vehicles*') ? 'active' : '' }}" href="/admin/vehicles">
                <i class="fas fa-car"></i>
                <span class="sidebar-text">Vehicles</span>
            </a>
            
            <!-- Auction -->
            <a class="nav-link {{ request()->is('admin/auctions*') ? 'active' : '' }}" href="/admin/auctions">
                <i class="fas fa-gavel"></i>
                <span class="sidebar-text">Auction</span>
            </a>
            
            <!-- Tenants -->
            <a class="nav-link {{ request()->is('admin/tenants*') ? 'active' : '' }}" href="/admin/tenants">
                <i class="fas fa-building"></i>
                <span class="sidebar-text">Tenants</span>
            </a>
            
            <!-- Users -->
            <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}" href="/admin/users">
                <i class="fas fa-users"></i>
                <span class="sidebar-text">Users</span>
            </a>
            
            <!-- Pages -->
            <a class="nav-link {{ request()->is('admin/pages*') ? 'active' : '' }}" href="/admin/pages">
                <i class="fas fa-file-alt"></i>
                <span class="sidebar-text">Pages</span>
            </a>
            
            <!-- Blogs Dropdown -->
            <div class="nav-item">
                <a class="nav-link {{ request()->is('admin/blogs*') || request()->is('admin/blog-categories*') ? 'active' : '' }}" 
                   data-bs-toggle="collapse" 
                   href="#blogsCollapse" 
                   role="button" 
                   aria-expanded="false" 
                   aria-controls="blogsCollapse">
                    <i class="fas fa-blog"></i>
                    <span class="sidebar-text">Blogs</span>
                    <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="blogsCollapse">
                    <div class="nav flex-column ms-3">
                        <a class="nav-link {{ request()->is('admin/blogs*') ? 'active' : '' }}" href="/admin/blogs">
                            <i class="fas fa-newspaper"></i>
                            <span class="sidebar-text">All Blog Posts</span>
                        </a>
                        <a class="nav-link {{ request()->is('admin/blogs/create*') ? 'active' : '' }}" href="/admin/blogs/create">
                            <i class="fas fa-plus"></i>
                            <span class="sidebar-text">Add New Post</span>
                        </a>
                        <a class="nav-link {{ request()->is('admin/blog-categories*') ? 'active' : '' }}" href="/admin/blog-categories">
                            <i class="fas fa-folder"></i>
                            <span class="sidebar-text">Categories</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Roles & Permissions -->
            <a class="nav-link {{ request()->is('admin/roles*') || request()->is('admin/permissions*') ? 'active' : '' }}" href="/admin/roles">
                <i class="fas fa-user-shield"></i>
                <span class="sidebar-text">Roles & Permissions</span>
            </a>
            
            <!-- Settings Dropdown -->
            <div class="nav-item">
                <a class="nav-link {{ request()->is('admin/languages*') || request()->is('admin/currencies*') || request()->is('admin/shipping*') || request()->is('admin/tax*') || request()->is('admin/payments*') || request()->is('admin/affiliates*') ? 'active' : '' }}" 
                   data-bs-toggle="collapse" 
                   href="#settingsCollapse" 
                   role="button" 
                   aria-expanded="false" 
                   aria-controls="settingsCollapse">
                    <i class="fas fa-cog"></i>
                    <span class="sidebar-text">Settings</span>
                    <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="settingsCollapse">
                    <div class="nav flex-column ms-3">
                        <a class="nav-link {{ request()->is('admin/languages*') ? 'active' : '' }}" href="/admin/languages">
                            <i class="fas fa-language"></i>
                            <span class="sidebar-text">Languages</span>
                        </a>
                        <a class="nav-link {{ request()->is('admin/currencies*') ? 'active' : '' }}" href="/admin/currencies">
                            <i class="fas fa-dollar-sign"></i>
                            <span class="sidebar-text">Currencies</span>
                        </a>
                        <a class="nav-link {{ request()->is('admin/shipping') && !request()->is('admin/shipping/carriers*') ? 'active' : '' }}" href="/admin/shipping">
                            <i class="fas fa-shipping-fast"></i>
                            <span class="sidebar-text">Shipping Zones</span>
                        </a>
                        <a class="nav-link {{ request()->is('admin/shipping/carriers*') ? 'active' : '' }}" href="/admin/shipping/carriers">
                            <i class="fas fa-truck"></i>
                            <span class="sidebar-text">Shipping Configuration</span>
                        </a>
                        <a class="nav-link {{ request()->is('admin/tax*') ? 'active' : '' }}" href="/admin/tax">
                            <i class="fas fa-calculator"></i>
                            <span class="sidebar-text">Vat & Tax</span>
                        </a>
                        <a class="nav-link {{ request()->is('admin/payments*') ? 'active' : '' }}" href="/admin/payments">
                            <i class="fas fa-credit-card"></i>
                            <span class="sidebar-text">Payment Gateway</span>
                        </a>
                        <a class="nav-link {{ request()->is('admin/affiliates*') ? 'active' : '' }}" href="/admin/affiliates">
                            <i class="fas fa-handshake"></i>
                            <span class="sidebar-text">Affiliate & Referral</span>
                        </a>
                        <a class="nav-link {{ request()->is('admin/cache*') ? 'active' : '' }}" href="/admin/cache">
                            <i class="fas fa-database"></i>
                            <span class="sidebar-text">Cache Management</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Logout Button -->
            <div class="mt-auto p-3">
                <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Are you sure you want to logout?')">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        <span class="sidebar-text">Logout</span>
                    </button>
                </form>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h4 mb-0 text-gray-800">@yield('title', 'Admin Panel')</h1>
                <div class="d-flex align-items-center">
                    <!-- Language Switcher -->
                    <div class="dropdown me-2">
                        <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-language"></i> {{ $currentLanguage ?? 'en' }}
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?lang=en">🇺🇸 English</a></li>
                            <li><a class="dropdown-item" href="?lang=es">🇪🇸 Español</a></li>
                            <li><a class="dropdown-item" href="?lang=fr">🇫🇷 Français</a></li>
                            <li><a class="dropdown-item" href="?lang=de">🇩🇪 Deutsch</a></li>
                            <li><a class="dropdown-item" href="?lang=ar">🇸🇦 العربية</a></li>
                            <li><a class="dropdown-item" href="?lang=zh">🇨🇳 中文</a></li>
                        </ul>
                    </div>
                    
                    <!-- Currency Switcher -->
                    <div class="dropdown me-3">
                        <button class="btn btn-outline-success btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-dollar-sign"></i> {{ $currentCurrency ?? 'USD' }}
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?currency=USD">$ USD</a></li>
                            <li><a class="dropdown-item" href="?currency=EUR">€ EUR</a></li>
                            <li><a class="dropdown-item" href="?currency=GBP">£ GBP</a></li>
                            <li><a class="dropdown-item" href="?currency=JPY">¥ JPY</a></li>
                        </ul>
                    </div>
                    
                    <span class="text-muted me-3">Welcome, Admin</span>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="container-fluid">
            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }

        // Auto-collapse sidebar on mobile
        if (window.innerWidth <= 768) {
            document.getElementById('sidebar').classList.add('collapsed');
            document.getElementById('mainContent').classList.add('expanded');
        }

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth <= 768) {
                document.getElementById('sidebar').classList.add('collapsed');
                document.getElementById('mainContent').classList.add('expanded');
            } else {
                document.getElementById('sidebar').classList.remove('collapsed');
                document.getElementById('mainContent').classList.remove('expanded');
            }
        });

        // Initialize dropdowns and handle active states
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-expand dropdowns if current page is in submenu
            const currentPath = window.location.pathname;
            
            // Check if we're on a products subpage
            if (currentPath.includes('/admin/products') || 
                currentPath.includes('/admin/brands') || 
                currentPath.includes('/admin/tags') || 
                currentPath.includes('/admin/categories')) {
                const productsCollapse = document.getElementById('productsCollapse');
                if (productsCollapse) {
                    productsCollapse.classList.add('show');
                    const productsLink = document.querySelector('[href="#productsCollapse"]');
                    if (productsLink) {
                        productsLink.setAttribute('aria-expanded', 'true');
                    }
                }
            }
            
                // Check if we're on a blogs subpage
                if (currentPath.includes('/admin/blogs') || 
                    currentPath.includes('/admin/blog-categories')) {
                    const blogsCollapse = document.getElementById('blogsCollapse');
                    if (blogsCollapse) {
                        blogsCollapse.classList.add('show');
                        const blogsLink = document.querySelector('[href="#blogsCollapse"]');
                        if (blogsLink) {
                            blogsLink.setAttribute('aria-expanded', 'true');
                        }
                    }
                }
                
                // Check if we're on a settings subpage
                if (currentPath.includes('/admin/languages') || 
                    currentPath.includes('/admin/currencies') || 
                    currentPath.includes('/admin/shipping') || 
                    currentPath.includes('/admin/tax') ||
                    currentPath.includes('/admin/payments') ||
                    currentPath.includes('/admin/affiliates')) {
                    const settingsCollapse = document.getElementById('settingsCollapse');
                    if (settingsCollapse) {
                        settingsCollapse.classList.add('show');
                        const settingsLink = document.querySelector('[href="#settingsCollapse"]');
                        if (settingsLink) {
                            settingsLink.setAttribute('aria-expanded', 'true');
                        }
                    }
                }
        });
    </script>
    
    @yield('scripts')
</body>
</html>

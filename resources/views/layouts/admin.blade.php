<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'WarungPOS') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <style>
        :root {
            --sidebar-bg: #191c24;
            --sidebar-hover: #2c2e33;
            --sidebar-text: #8d8d8d;
            --sidebar-active: #6f42c1;
            --topbar-bg: #ffffff;
            --body-bg: #f4f5f7;
            --card-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.08);
            --primary: #6f42c1;
            --primary-hover: #5a32a3;
            --success: #00d25b;
            --warning: #ffab00;
            --danger: #fc424a;
            --info: #8f5fe8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--body-bg);
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100vh;
            background: var(--sidebar-bg);
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-brand h4 {
            color: #fff;
            font-weight: 700;
            margin: 0;
            font-size: 1.25rem;
        }

        .sidebar-brand i {
            color: var(--primary);
            font-size: 1.5rem;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-section {
            padding: 0.5rem 1.5rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: rgba(255, 255, 255, 0.3);
            margin-top: 0.5rem;
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: var(--sidebar-text);
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 0.875rem;
        }

        .sidebar-nav .nav-link:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .sidebar-nav .nav-link.active {
            background: linear-gradient(90deg, var(--primary), var(--info));
            color: #fff;
        }

        .sidebar-nav .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
            font-size: 0.9rem;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Topbar */
        .topbar {
            background: var(--topbar-bg);
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .topbar-left h5 {
            margin: 0;
            font-weight: 600;
            color: #333;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            background: var(--body-bg);
            cursor: pointer;
            text-decoration: none;
            color: #333;
        }

        .user-dropdown:hover {
            background: #e9ecef;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
            font-size: 0.875rem;
        }

        /* Content */
        .content {
            padding: 1.5rem;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: var(--card-shadow);
            background: #fff;
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid #eee;
            padding: 1rem 1.5rem;
            font-weight: 600;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Stat Cards */
        .stat-card {
            border-radius: 0.75rem;
            padding: 1.5rem;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-card.primary {
            background: linear-gradient(135deg, var(--primary), var(--info));
        }

        .stat-card.success {
            background: linear-gradient(135deg, #00d25b, #05c754);
        }

        .stat-card.warning {
            background: linear-gradient(135deg, #ffab00, #ffc107);
        }

        .stat-card.danger {
            background: linear-gradient(135deg, #fc424a, #e8363d);
        }

        .stat-card .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-card .stat-content h3 {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0;
        }

        .stat-card .stat-content p {
            margin: 0;
            opacity: 0.9;
            font-size: 0.875rem;
        }

        /* Tables */
        .table {
            margin: 0;
        }

        .table th {
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #666;
            border-bottom: 2px solid #eee;
            padding: 1rem;
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
        }

        /* Buttons */
        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        /* Forms */
        .form-control,
        .form-select {
            border-radius: 0.5rem;
            border: 1px solid #ddd;
            padding: 0.625rem 1rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(111, 66, 193, 0.1);
        }

        .form-label {
            font-weight: 500;
            font-size: 0.875rem;
            color: #555;
            margin-bottom: 0.5rem;
        }

        /* Badge */
        .badge {
            font-weight: 500;
            padding: 0.35rem 0.65rem;
            border-radius: 0.375rem;
        }

        /* Toast */
        .toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 9999;
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-store"></i>
            <h4>WarungPOS</h4>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-section">Menu Utama</div>
            <a href="{{ route('admin.dashboard') }}"
                class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('pos.index') }}" class="nav-link">
                <i class="fas fa-cash-register"></i>
                <span>POS Kasir</span>
            </a>

            <div class="nav-section">Master Data</div>
            <a href="{{ route('admin.categories.index') }}"
                class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i>
                <span>Kategori</span>
            </a>
            <a href="{{ route('admin.products.index') }}"
                class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="fas fa-utensils"></i>
                <span>Produk/Menu</span>
            </a>

            <div class="nav-section">Transaksi</div>
            <a href="{{ route('admin.transactions.index') }}"
                class="nav-link {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
                <i class="fas fa-receipt"></i>
                <span>Riwayat Transaksi</span>
            </a>

            <div class="nav-section">Akun</div>
            <a href="{{ route('profile.edit') }}" class="nav-link">
                <i class="fas fa-user-cog"></i>
                <span>Profil</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <a href="{{ route('logout') }}" class="nav-link"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </form>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-left">
                <button class="btn btn-link text-dark d-lg-none" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h5>@yield('title', 'Dashboard')</h5>
            </div>
            <div class="topbar-right">
                <div class="dropdown">
                    <a href="#" class="user-dropdown dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i
                                    class="fas fa-user me-2"></i>Profil</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="content">
            <!-- Toast Container -->
            <div class="toast-container">
                @if(session('success'))
                    <div class="toast show align-items-center text-bg-success border-0" role="alert">
                        <div class="d-flex">
                            <div class="toast-body">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto"
                                data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                @endif
                @if(session('error'))
                    <div class="toast show align-items-center text-bg-danger border-0" role="alert">
                        <div class="d-flex">
                            <div class="toast-body">
                                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto"
                                data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                @endif
            </div>

            @yield('content')
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Auto-hide toasts
        setTimeout(() => {
            document.querySelectorAll('.toast').forEach(toast => {
                new bootstrap.Toast(toast).hide();
            });
        }, 5000);
    </script>
    @stack('scripts')
</body>

</html>
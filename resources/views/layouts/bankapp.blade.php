<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>APARA Claim Dashboard - Bank Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8f9fa;
    }

    .navbar {
        background-color: #1a3e72;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 0.5rem 1rem;
    }

    .navbar-brand {
        color: #ffffff !important;
        font-weight: 600;
        font-size: 1.4rem;
        cursor: default;
    }

    .sidebar {
        width: 250px;
        min-height: 100vh;
        background-color: #ffffff;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.05);
    }

    .sidebar .nav-link {
        color: #2c3e50;
        font-weight: 500;
        padding: 10px 15px;
        border-radius: 0.5rem;
        transition: background-color 0.2s, color 0.2s;
    }

    .sidebar .nav-link:hover {
        background-color: #f1f3f5;
        color: #1a3e72;
    }

    .sidebar .nav-link.active {
        background-color: #e3f0ff;
        color: #1a3e72;
        font-weight: 600;
    }

    .sidebar .bi {
        font-size: 1.1rem;
        margin-right: 8px;
        color: #1a3e72;
    }

    .profile-img {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 2px solid rgba(255,255,255,0.5);
    }

    .bank-container {
        background: #ffffff;
        padding: 2rem;
        border-radius: 0.75rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border-left: 4px solid #1a3e72;
    }

    .bank-header {
        color: #1a3e72;
        border-bottom: 1px solid #e0e0e0;
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
    }

    @media (min-width: 768px) {
        main {
            padding: 2.5rem;
        }
    }

    .bank-alert {
        background-color: #e3f0ff;
        border-left: 4px solid #1a3e72;
        color: #1a3e72;
    }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <span class="navbar-brand">APARA Bank Portal</span>
            <div class="d-flex align-items-center ms-auto">
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                        <img src="https://via.placeholder.com/32" alt="Profile" class="profile-img me-2">
                        <strong>{{ $username ?? 'Bank User' }}</strong>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-bank me-2"></i>Bank Details</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-box-arrow-right me-2"></i>Sign out</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar p-3">
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a class="nav-link active" href="{{ route('claims.create') }}">
                        <i class="bi bi-speedometer2"></i> Overdue
                    </a>
                </li>
                <li class="nav-item mb-2">
    <a class="nav-link {{ request()->routeIs('claims.pending') ? 'active' : '' }}" 
       href="{{ route('claims.pending') }}">
       <i class="bi bi-file-earmark-text me-2"></i> Pending Overdue 
    </a>
</li>
                
                
            </ul>
        </div>

        <!-- Main content -->
        <main class="flex-fill p-4">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
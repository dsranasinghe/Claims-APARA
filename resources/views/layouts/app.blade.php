<!-- app.blade.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>APARA Claim Dashboard</title>
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
            background-color: #f1f3f5;
        }

        .navbar {
            background-color: #001f3f;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
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
            box-shadow: 0 0 0 1px #e0e0e0;
        }

        .sidebar .nav-link {
            color: #136880ff;
            font-weight: 500;
            padding: 10px 15px;
            border-radius: 0.5rem;
            transition: background-color 0.2s, color 0.2s;
        }

        .sidebar .nav-link:hover {
            background-color: #f1f3f5;
        }

        .sidebar .nav-link.active {
            background-color: #e9f0ff;
            color: #121d29ff;
        }

        .sidebar .bi {
            font-size: 1.1rem;
        }

        .profile-img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.5);
        }

        .modern-container {
            background: #ffffff;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
        }

        @media (min-width: 768px) {
            main {
                padding: 3rem;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <span class="navbar-brand">APARA Claims</span>
            <div class="d-flex align-items-center ms-auto">
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                        <img src="https://via.placeholder.com/32" alt="Profile" class="profile-img me-2">
                        <strong>{{ session('username', 'Guest') }}</strong>

                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow">

                        <li>
                            <form id="logout-form" action="{{ route('signout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right me-2"></i>Sign out
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="d-flex">
        <!-- Sidebar -->
        <x-sidebar />

        <!-- Main content -->
        <main class="flex-fill p-4">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
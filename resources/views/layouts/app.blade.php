<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aplikasi Peminjaman Lab')</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8fafc;
            color: #1f2937;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .navbar {
            background-color: #ffffff;
            border-bottom: 1px solid #e5e7eb;
        }

        .navbar-brand {
            font-weight: 600;
            color: #0d6efd !important;
        }

        .nav-link {
            color: #374151 !important;
            font-weight: 500;
            transition: color 0.2s ease, background-color 0.2s ease;
            border-radius: 8px;
            padding: 6px 12px !important;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #0d6efd !important;
            background-color: #e9f2ff;
        }

        .dropdown-menu {
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        }

        main {
            flex: 1;
            min-height: 100vh;
            /* Tinggi minimal layar penuh */
            padding-top: 2rem;
            padding-bottom: 2rem;
        }

        footer {
            background-color: #ffffff;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>

<body>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                Lab Inventory
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('items*') ? 'active' : '' }}" href="{{ route('items.index') }}">
                            Barang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('loans*') ? 'active' : '' }}" href="{{ route('loans.index') }}">
                            Peminjaman
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('employees*') ? 'active' : '' }}" href="{{ route('employees.index') }}">
                            Karyawan
                        </a>
                    </li>
                    <!-- <li class="nav-item">
                    <a class="dropdown-item text-danger" href="#">Keluar</a></li> -->
                </ul>
            </div>
        </div>
    </nav>

    {{-- Content --}}
    <main class="container">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="py-3 mt-auto">
        <div class="container text-center text-muted small">
            © {{ date('Y') }} <strong>Lab Komputer Kampus</strong> · Aplikasi Peminjaman Barang
        </div>
    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
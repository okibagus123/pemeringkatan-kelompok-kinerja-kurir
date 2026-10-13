<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SPK Kinerja Kurir') }} - Sistem Pendukung Keputusan</title>

    <!-- Fonts Kesenian Modern -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Poppins:wght@500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ARTISTIC GRADIENT & 3D CSS OVERRIDE -->
    <style>
        body {
            /* Background gradasi super halus untuk body */
            background: linear-gradient(120deg, #fdfbfb 0%, #ebedee 100%);
            color: #2d3436;
            font-family: 'Nunito', sans-serif;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6, .navbar-brand, .fw-bold {
            font-family: 'Poppins', sans-serif;
        }

        /* ==========================================
           ANIMASI GRADASI BERGERAK (HEADER & FOOTER)
           ========================================== */
        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .animated-gradient-bg {
            background: linear-gradient(-45deg, #4facfe, #667eea, #764ba2, #00f2fe);
            background-size: 400% 400%;
            animation: gradientMove 12s ease infinite;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* Penyesuaian Teks Navbar agar Terlihat Mewah di Atas Gradasi */
        .navbar-brand {
            color: #ffffff !important;
            font-size: 1.3rem;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .nav-link {
            font-weight: 600;
            color: rgba(255, 255, 255, 0.8) !important; /* Putih agak transparan */
            transition: all 0.3s ease;
            padding: 8px 16px !important;
            margin: 0 4px;
        }

        .nav-link:hover, .nav-link.active {
            color: #ffffff !important; /* Putih terang saat disorot */
            background: rgba(255, 255, 255, 0.2); /* Efek kaca transparan */
            border-radius: 12px;
            transform: translateY(-2px);
        }

        .navbar-toggler {
            background-color: rgba(255, 255, 255, 0.2);
            border: none;
        }
        .navbar-toggler-icon {
            filter: brightness(0) invert(1); /* Mengubah ikon hamburger menjadi putih */
        }

        /* Kartu dengan sudut lebih bulat (Artistic Shape) */
        .card {
            border: none;
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            box-shadow: 10px 10px 25px rgba(209, 217, 230, 0.6), 
                       -10px -10px 25px rgba(255, 255, 255, 0.9);
            transition: all 0.4s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px) scale(1.01);
            box-shadow: 15px 15px 35px rgba(209, 217, 230, 0.7), 
                       -15px -15px 35px rgba(255, 255, 255, 1);
        }

        .card-header {
            background: transparent !important;
            border-bottom: 2px dashed rgba(0,0,0,0.05);
            padding: 1.5rem;
        }

        /* Otomatis Menyulap Semua Tabel (Normalisasi, K-Means, TOPSIS) */
        .table {
            vertical-align: middle;
        }
        
        .table-dark th, thead.table-dark {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%) !important;
            color: #ffffff !important;
            border: none;
            letter-spacing: 1px;
        }

        .table-info th, thead.table-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
            color: #ffffff !important;
            border: none;
        }

        .table-warning th, thead.table-warning {
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%) !important;
            color: #ffffff !important;
            border: none;
        }

        /* Otomatis Menyulap Semua Badge Cluster */
        .badge {
            border-radius: 20px;
            padding: 0.5em 1em;
            font-weight: 700;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .badge.bg-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; }
        .badge.bg-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important; }
        .badge.bg-danger { background: linear-gradient(135deg, #ff0844 0%, #ffb199 100%) !important; }
        .badge.bg-warning { background: linear-gradient(135deg, #f6d365 0%, #fda085 100%) !important; color: #fff !important; }
        .badge.bg-info { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%) !important; color: #1f2937 !important;}

        /* Otomatis Menyulap Tombol-Tombol Aksi */
        .btn {
            border-radius: 12px;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            letter-spacing: 0.5px;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; }
        .btn-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important; }
        .btn-warning { background: linear-gradient(135deg, #f6d365 0%, #fda085 100%) !important; color: white !important;}
        
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            filter: brightness(1.1);
        }

        /* Mempercantik Alert/Kotak Info */
        .alert-info {
            background: linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%) !important;
            border: none;
            color: #1e293b;
            border-radius: 16px;
            box-shadow: inset 0 0 20px rgba(255,255,255,0.5);
        }
        .alert-success {
            background: linear-gradient(135deg, #d4fc79 0%, #96e6a1 100%) !important;
            border: none;
            color: #1e293b;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

 <!-- NAVBAR UTAMA -->
    <nav class="navbar navbar-expand-lg sticky-top py-3 animated-gradient-bg shadow-sm">
        <div class="container-fluid px-4 d-flex justify-content-between align-items-center">
            
            <!-- 1. Sisi Kiri: Logo (Diberi flex: 1 agar seimbang dengan sisi kanan) -->
            <div class="d-flex align-items-center" style="flex: 1;">
                <a class="navbar-brand d-flex align-items-center fw-bold text-white m-0" href="{{ url('/') }}">
                    <div class="text-primary rounded-circle d-flex align-items-center justify-content-center me-2 shadow-sm" style="width: 38px; height: 38px; background: rgba(255,255,255,0.9);">
                        <i class="fas fa-truck-fast"></i>
                    </div>
                    <span class="fs-6 d-none d-xl-inline">KINERJA KURIR GOTO LOGISTIK</span>
                </a>
            </div>
            
            <!-- Tombol Hamburger untuk Tampilan Mobile -->
            <button class="navbar-toggler border-0 text-white shadow-none d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars fa-lg"></i>
            </button>

            <!-- 2. Menu Navigasi Utama (Diposisikan Tepat di Tengah Layar) -->
            <div class="collapse navbar-collapse justify-content-center flex-grow-0" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-1">
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 rounded-3 d-flex align-items-center text-white {{ request()->is('/') ? 'active bg-white bg-opacity-25' : '' }}" href="{{ url('/') }}">
                            <i class="fas fa-chart-pie me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 rounded-3 d-flex align-items-center text-white {{ request()->is('input-data*') ? 'active bg-white bg-opacity-25' : '' }}" href="{{ url('/input-data') }}">
                            <i class="fas fa-database me-2"></i> Input Data
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 rounded-3 d-flex align-items-center text-white {{ request()->is('normalisasi*') ? 'active bg-white bg-opacity-25' : '' }}" href="{{ url('/normalisasi') }}">
                            <i class="fas fa-calculator me-2"></i> Normalisasi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 rounded-3 d-flex align-items-center text-white {{ request()->is('k-means*') ? 'active bg-white bg-opacity-25' : '' }}" href="{{ url('/k-means') }}">
                            <i class="fas fa-project-diagram me-2"></i> K-Means & DBI
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 rounded-3 d-flex align-items-center text-white {{ request()->is('topsis*') ? 'active bg-empty bg-white bg-opacity-25' : '' }}" href="{{ url('/topsis') }}">
                            <i class="fas fa-sliders-h me-2"></i> TOPSIS
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 rounded-3 d-flex align-items-center text-white {{ request()->is('hasil-akhir*') ? 'active bg-white bg-opacity-25' : '' }}" href="{{ url('/hasil-akhir') }}">
                            <i class="fas fa-trophy me-2"></i> Hasil Akhir
                        </a>
                    </li>
                </ul>
            </div>

            <!-- 3. Sisi Kanan: Profil Pengguna (Diberi flex: 1 agar ukurannya menyeimbangkan sisi kiri) -->
            <div class="d-flex align-items-center justify-content-end" style="flex: 1;">
                <ul class="navbar-nav mb-2 mb-lg-0 align-items-lg-center">
                    @auth
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center text-white px-3 py-2 rounded-pill shadow-sm" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background: rgba(255,255,255,0.2);">
                                <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center me-2 shadow-sm" style="width: 28px; height: 28px; font-weight: 800; font-size: 0.8rem;">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="fw-semibold">{{ Auth::user()->name }}</span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-2 p-2" style="background: rgba(255,255,255,0.98); backdrop-filter: blur(10px);">
                                <div class="px-3 py-2 text-muted small border-bottom mb-2">
                                    Login sebagai:<br><strong class="text-dark">{{ Auth::user()->email }}</strong>
                                </div>
                                <a class="dropdown-item rounded-2 py-2 text-dark fw-medium" href="{{ route('profile') }}">
                                    <i class="fas fa-user-cog text-primary me-2"></i> Pengaturan Akun
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item rounded-2 py-2 text-danger fw-bold" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout System
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endauth

                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item"><a class="nav-link text-white" href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-1"></i> Login</a></li>
                        @endif
                        @if (Route::has('register'))
                            <li class="nav-item"><a class="nav-link btn btn-light text-primary px-3 ms-2 fw-bold rounded-pill" href="{{ route('register') }}">Register</a></li>
                        @endif
                    @endguest
                </ul>
            </div>

        </div>
    </nav>

    <!-- KONTEN UTAMA HALAMAN -->
    <main class="container py-4 flex-grow-1">
        @yield('content')
    </main>

    <!-- FOOTER (Dengan Class Animasi Gradasi) -->
    <footer class="text-center py-4 mt-auto animated-gradient-bg text-white">
        <div class="container">
            <p class="mb-0 fw-medium text-white" style="text-shadow: 0 1px 3px rgba(0,0,0,0.2);">
                <i class="fas fa-heart text-danger me-1"></i> &copy; 2026 Sistem Pendukung Keputusan Kinerja Kurir (K-Means & TOPSIS). All Rights Reserved.
            </p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
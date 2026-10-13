<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Autentikasi - SPK Kinerja Kurir</title>

    <!-- Fonts Kesenian Modern -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Poppins:wght@500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Animasi Background Full Screen */
        body {
            font-family: 'Nunito', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            background: linear-gradient(-45deg, #4facfe, #667eea, #764ba2, #00f2fe);
            background-size: 400% 400%;
            animation: gradientMove 15s ease infinite;
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        h1, h2, h3, h4, h5, h6, .fw-bold {
            font-family: 'Poppins', sans-serif;
        }

        /* Kotak Kaca 3D (Glassmorphism) Khusus Login/Register */
        .auth-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.5);
            width: 100%;
            max-width: 450px;
            padding: 2.5rem;
            transform: translateY(0);
            transition: all 0.4s ease;
        }

        .auth-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        }

        .form-control {
            border-radius: 12px;
            padding: 0.75rem 1rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            transition: all 0.3s;
        }

        .form-control:focus {
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
            border-color: #667eea;
            background: #ffffff;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            border-radius: 12px;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            padding: 0.75rem;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
            color: white;
        }

        .btn-gradient-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            box-shadow: 0 4px 15px rgba(17, 153, 142, 0.4);
        }

        .btn-gradient-success:hover {
            box-shadow: 0 8px 25px rgba(17, 153, 142, 0.6);
        }
    </style>
</head>
<body>

    <div class="container d-flex flex-column align-items-center">
        <!-- Area Alert / Notifikasi Error/Success -->
        <div class="w-100" style="max-width: 450px;">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-4" style="background: rgba(255,255,255,0.9);"><i class="fas fa-check-circle me-2 text-success"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-4" style="background: rgba(255,255,255,0.9);"><i class="fas fa-exclamation-triangle me-2 text-danger"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-4" style="background: rgba(255,255,255,0.9);">
                    <i class="fas fa-times-circle me-2 text-danger"></i>Terjadi kesalahan pengisian form.<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>

        <!-- Ruang untuk Form Login/Register -->
        @yield('content')
        
        <div class="mt-4 text-white text-center" style="opacity: 0.8; font-size: 0.85rem; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
            &copy; 2026 SPK Kinerja Kurir (K-Means & TOPSIS).
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
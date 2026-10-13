@extends('layouts.auth')
@section('content')

<div class="auth-card text-center">
    <div class="text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-lg" style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <i class="fas fa-truck-fast fa-2x"></i>
    </div>
    
    <h3 class="fw-bold mb-1 text-dark">Selamat Datang</h3>
    <p class="text-muted mb-4 small">Masuk ke sistem evaluasi kinerja</p>
    
    <form action="{{ route('login.post') }}" method="POST">
        @csrf
        <div class="mb-3 text-start">
            <label class="form-label fw-bold text-muted small ms-1">Alamat Email</label>
            <input type="email" name="email" class="form-control" placeholder="Masukkan email Anda" required autofocus>
        </div>
        <div class="mb-4 text-start">
            <label class="form-label fw-bold text-muted small ms-1">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
        </div>
        
        <button type="submit" class="btn btn-gradient w-100 mb-3 fs-5">Login ke Dashboard</button>
        
        <p class="text-muted small mb-0">
            Belum memiliki akun? <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Daftar sekarang</a>
        </p>
    </form>
</div>

@endsection
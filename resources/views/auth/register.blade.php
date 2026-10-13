@extends('layouts.auth')
@section('content')

<div class="auth-card text-center" style="max-width: 500px;">
    <div class="text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-lg" style="width: 80px; height: 80px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
        <i class="fas fa-user-plus fa-2x"></i>
    </div>
    
    <h3 class="fw-bold mb-1 text-dark">Daftar Akun Baru</h3>
    <p class="text-muted mb-4 small">Lengkapi data di bawah untuk bergabung</p>
    
    <form action="{{ route('register.post') }}" method="POST">
        @csrf
        <div class="mb-3 text-start">
            <label class="form-label fw-bold text-muted small ms-1">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" placeholder="Contoh: John Doe" required>
        </div>
        <div class="mb-3 text-start">
            <label class="form-label fw-bold text-muted small ms-1">Alamat Email</label>
            <input type="email" name="email" class="form-control" placeholder="email@perusahaan.com" required>
        </div>
        <div class="row">
            <div class="col-6 mb-4 text-start">
                <label class="form-label fw-bold text-muted small ms-1">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required>
            </div>
            <div class="col-6 mb-4 text-start">
                <label class="form-label fw-bold text-muted small ms-1">Ulangi Password</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Ketik ulang" required>
            </div>
        </div>
        
        <button type="submit" class="btn btn-gradient btn-gradient-success w-100 mb-3 fs-5">Buat Akun Sekarang</button>
        
        <p class="text-muted small mb-0">
            Sudah memiliki akun? <a href="{{ route('login') }}" class="text-success fw-bold text-decoration-none">Masuk di sini</a>
        </p>
    </form>
</div>

@endsection
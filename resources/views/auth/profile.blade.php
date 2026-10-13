@extends('layouts.app')
@section('content')
<div class="row justify-content-center align-items-center my-4">
    <div class="col-lg-6 col-md-8">
        <div class="card shadow-lg border-0 p-3" style="border-radius: 25px;">
            <div class="card-header text-center border-0 bg-transparent pt-4 pb-0">
                <!-- Lingkaran Inisial Nama (Dipusatkan Sempurna) -->
                <div class="text-white rounded-circle d-inline-flex align-items-center justify-content-center shadow-lg mb-3 mx-auto" style="width: 90px; height: 90px; font-size: 2.5rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <h3 class="fw-bold mb-1 text-dark">{{ Auth::user()->name }}</h3>
                <p class="text-muted mb-0"><i class="fas fa-envelope me-1"></i> {{ Auth::user()->email }}</p>
            </div>
            
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4 border-bottom pb-2 text-dark"><i class="fas fa-user-edit me-2 text-warning"></i>Edit Informasi Profil</h5>
                
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small">Alamat Email</label>
                        <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}" required>
                    </div>
                    <button type="submit" class="btn btn-warning w-100 rounded-pill fs-5 py-2 text-white shadow-sm"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
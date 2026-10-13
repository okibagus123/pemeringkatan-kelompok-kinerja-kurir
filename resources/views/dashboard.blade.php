@extends('layouts.app')
@section('content')

<div class="row mb-5 mt-3">
    <div class="col-md-12 text-center">
        <h2 class="fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
            Selamat Datang di Sistem Pemeringkatan Tiap Kelompok Kinerja Kurir
        </h2>
        <p class="text-muted fs-5">Sistem Evaluasi Kinerja menggunakan algoritma K-Means dan TOPSIS.</p>
    </div>
</div>

<div class="row justify-content-center">
    <!-- Kartu 1: Total Kurir (Gradasi Ungu) -->
    <div class="col-md-4 mb-4">
        <div class="card text-white h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 25px;">
            <div class="card-body d-flex flex-column justify-content-center align-items-center py-5 position-relative overflow-hidden">
                <i class="fas fa-users position-absolute" style="font-size: 8rem; opacity: 0.1; right: -20px; bottom: -20px;"></i>
                <h5 class="fw-bold mb-1"><i class="fas fa-user-tie me-2"></i>Total Kurir Aktif</h5>
                <h1 class="display-3 fw-bold mb-0">{{ $totalKurir ?? 0 }}</h1>
            </div>
        </div>
    </div>

    <!-- Kartu 2: Total Paket (Gradasi Tropis) -->
    <div class="col-md-4 mb-4">
        <div class="card text-white h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 25px;">
            <div class="card-body d-flex flex-column justify-content-center align-items-center py-5 position-relative overflow-hidden">
                <i class="fas fa-box-open position-absolute" style="font-size: 8rem; opacity: 0.1; right: -20px; bottom: -20px;"></i>
                <h5 class="fw-bold mb-1"><i class="fas fa-boxes me-2"></i>Total Pengiriman</h5>
                <h1 class="display-3 fw-bold mb-0">{{ number_format($totalPaket ?? 0) }}</h1>
            </div>
        </div>
    </div>

    <!-- Kartu 3: Optimal K (Gradasi Matahari) -->
    <div class="col-md-4 mb-4">
        <div class="card text-white h-100" style="background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); border-radius: 25px;">
            <div class="card-body d-flex flex-column justify-content-center align-items-center py-5 position-relative overflow-hidden">
                <i class="fas fa-chart-network position-absolute" style="font-size: 8rem; opacity: 0.1; right: -20px; bottom: -20px;"></i>
                <h5 class="fw-bold mb-1"><i class="fas fa-check-circle me-2"></i>Cluster Optimal (DBI)</h5>
                <h1 class="display-3 fw-bold mb-0">K = {{ $bestK ?? '-' }}</h1>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12 text-center">
        <a href="{{ url('/input-data') }}" class="btn btn-primary btn-lg px-5 py-3 rounded-pill shadow-lg">
            <i class="fas fa-play-circle me-2 fs-5"></i> Mulai Analisis Kinerja
        </a>
    </div>
</div>
@endsection
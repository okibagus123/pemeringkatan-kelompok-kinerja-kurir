@extends('layouts.app')
@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<!-- BAGIAN 1: TABEL PUSAT CENTROID (Otomatis dari K-Means Aktif) -->
<div class="card shadow-sm border-info mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold"><i class="fas fa-dot-circle text-info me-2"></i>Pusat Centroid (Alternatif TOPSIS)</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle mb-0">
                <thead class="table-info">
                    <tr>
                        <th>ID Cluster</th>
                        <th>Avg Paket (Norm)</th>
                        <th>Avg Tepat Waktu (Norm)</th>
                        <th>Avg Telat (Norm)</th>
                        <th>Avg Gagal (Norm)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($centroids as $cId => $c)
                    <tr>
                        <td class="fw-bold text-primary">Cluster {{ $cId }}</td>
                        <td>{{ number_format($c['paket'], 4) }}</td>
                        <td>{{ number_format($c['tepat'], 4) }}</td>
                        <td>{{ number_format($c['telat'], 4) }}</td>
                        <td>{{ number_format($c['gagal'], 4) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-4 text-muted">Data centroid kosong. Pastikan Anda sudah menjalankan K-Means.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- BAGIAN 2: FORM BOBOT & COST/BENEFIT -->
<div class="card shadow-sm border-primary mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold"><i class="fas fa-sliders-h text-primary me-2"></i>Pengaturan Kriteria TOPSIS</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('topsis.run') }}" method="POST">
            @csrf
            <div class="row">
                <!-- Paket -->
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Orderan Paket</label>
                    <input type="number" step="0.1" class="form-control mb-2" name="bobot_paket" placeholder="Isi Bobot (cth: 4)" required>
                    <select class="form-select text-success" name="jenis_paket" required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="benefit">BENEFIT</option>
                        <option value="cost">COST</option>
                    </select>
                </div>
                <!-- Tepat Waktu -->
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Paket Tepat</label>
                    <input type="number" step="0.1" class="form-control mb-2" name="bobot_tepat" placeholder="Isi Bobot (cth: 5)" required>
                    <select class="form-select text-success" name="jenis_tepat" required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="benefit">BENEFIT</option>
                        <option value="cost">COST</option>
                    </select>
                </div>
                <!-- Telat -->
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Paket Telat</label>
                    <input type="number" step="0.1" class="form-control mb-2" name="bobot_telat" placeholder="Isi Bobot (cth: 3)" required>
                    <select class="form-select text-danger" name="jenis_telat" required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="cost">COST</option>
                        <option value="benefit">BENEFIT</option>
                    </select>
                </div>
                <!-- Gagal -->
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Paket Gagal</label>
                    <input type="number" step="0.1" class="form-control mb-2" name="bobot_gagal" placeholder="Isi Bobot (cth: 4)" required>
                    <select class="form-select text-danger" name="jenis_gagal" required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="cost">COST</option>
                        <option value="benefit">BENEFIT</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3 shadow-sm" {{ empty($centroids) ? 'disabled' : '' }}>
                <i class="fas fa-sort-numeric-down me-1"></i> Hitung Ranking TOPSIS
            </button>
        </form>
    </div>
</div>

<!-- BAGIAN 3: TABEL HASIL RANKING -->
<div class="card shadow-sm border-success mb-5">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold"><i class="fas fa-trophy text-success me-2"></i>Hasil Ranking Kelompok (TOPSIS)</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover text-center align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Ranking</th>
                        <th>ID Cluster</th>
                        <th>Nilai Preferensi (V)</th>
                    </tr>
                </thead>
                <tbody>
                   @forelse($hasilTopsis as $hasil)
                    <tr>
                        <td class="fw-bold fs-5">
                            @if($hasil->ranking == 1) <i class="fas fa-medal text-warning me-1"></i> @endif
                            #{{ $hasil->ranking }}
                        </td>
                        <td class="fw-bold text-primary">Cluster {{ $hasil->cluster }}</td>
                        <td class="fw-bold {{ $hasil->ranking == 1 ? 'text-success' : '' }} fs-5">
                            {{ number_format($hasil->nilai_preferensi, 5) }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="py-5 text-muted"><i class="fas fa-folder-open mb-2 fs-3 d-block"></i>Data TOPSIS masih kosong. Silakan isi bobot dan hitung di atas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')
@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<!-- BAGIAN 1: PENGATURAN K-MEANS & CENTROID -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm border-primary">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-cogs text-primary me-2"></i>Tahap 2: Pengaturan K-Means & Centroid Awal</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('kmeans.run') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Pilih Nilai K (Jumlah Cluster)</label>
                            <select class="form-select border-primary" name="k_value" id="k_value" onchange="updateCentroids()" required>
                                <option value="3" {{ old('k_value') == 3 ? 'selected' : '' }}>K = 3</option>
                                <option value="4" {{ old('k_value') == 4 ? 'selected' : '' }}>K = 4</option>
                                <option value="5" {{ old('k_value') == 5 ? 'selected' : '' }}>K = 5</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3" id="centroid_container">
                        @for($i=1; $i<=5; $i++)
                        <div class="col-md-4 mb-3" id="centroid_wrapper_{{ $i }}">
                            <label class="form-label fw-bold">Centroid {{ $i }} (ID Kurir)</label>
                            <select class="form-select" name="centroid[]" id="c_{{ $i }}">
                                <option value="">-- Pilih Pusat Cluster --</option>
                                @foreach($normalisasi as $norm)
                                    <option value="{{ $norm->kurir->id }}" {{ old('centroid.' . ($i - 1)) == $norm->kurir->id ? 'selected' : '' }}>
                                        {{ $norm->kurir->id_kurir }} ({{ $norm->kurir->nama_kurir }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endfor
                    </div>
                    
                    <button type="submit" class="btn btn-primary shadow-sm mb-3">
                        <i class="fas fa-project-diagram me-1"></i> Hitung K-Means
                    </button>
                </form>

             <!-- TABEL REFERENSI DATA NORMALISASI (Menampilkan ±15 baris, sisanya bisa di-scroll) -->
                <hr class="my-4">
                <h6 class="fw-bold mb-3"><i class="fas fa-table text-secondary me-2"></i>Referensi Data Normalisasi (Untuk Memilih Centroid)</h6>
                <div class="table-responsive border rounded bg-white" style="max-height: 320px; overflow-y: auto;">
                    <table class="table table-sm table-striped table-hover text-center align-middle mb-0" style="font-size: 0.9rem;">
                        <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                            <tr>
                                <th>No</th>
                                <th>ID Kurir</th>
                                <th>Nama Kurir</th>
                                <th>Paket (Norm)</th>
                                <th>Tepat Waktu (Norm)</th>
                                <th>Telat (Norm)</th>
                                <th>Gagal (Norm)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($normalisasi as $index => $norm)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="fw-bold">{{ $norm->kurir->id_kurir ?? '-' }}</td>
                                <td class="text-start">{{ $norm->kurir->nama_kurir ?? '-' }}</td>
                                <td class="text-primary">{{ number_format($norm->jumlah_paket_norm, 4) }}</td>
                                <td class="text-success">{{ number_format($norm->tepat_waktu_norm, 4) }}</td>
                                <td class="text-warning text-dark">{{ number_format($norm->telat_norm, 4) }}</td>
                                <td class="text-danger">{{ number_format($norm->gagal_norm, 4) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-4 text-muted">Data normalisasi kosong. Silakan jalankan tahap Normalisasi terlebih dahulu.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- BAGIAN 2: HASIL PENGELOMPOKAN K-MEANS -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap">
                <h5 class="mb-0 fw-bold"><i class="fas fa-users text-success me-2"></i>Hasil Pengelompokan (K-Means)</h5>
                
                <!-- INFORMASI JUMLAH ANGGOTA CLUSTER & ITERASI -->
                @if($hasilClusters->isNotEmpty())
                <div class="d-flex align-items-center flex-wrap gap-2 mt-2 mt-md-0">
                    <span class="badge bg-dark px-3 py-2 fs-6">
                        <i class="fas fa-sync-alt me-1"></i> Iterasi Konvergen: <b>{{ $iterations }}x</b>
                    </span>
                    @foreach($clusterCounts as $cId => $count)
                        @php
                            $badgeColor = 'bg-secondary';
                            if($cId == 1) $badgeColor = 'bg-primary';
                            elseif($cId == 2) $badgeColor = 'bg-success';
                            elseif($cId == 3) $badgeColor = 'bg-danger';
                            elseif($cId == 4) $badgeColor = 'bg-warning text-dark';
                            elseif($cId == 5) $badgeColor = 'bg-info text-dark';
                        @endphp
                        <span class="badge {{ $badgeColor }} px-3 py-2 fs-6 shadow-sm">
                            Cluster {{ $cId }}: <b>{{ $count }} Kurir</b>
                        </span>
                    @endforeach
                </div>
                @endif
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive border rounded" style="max-height: 450px; overflow-y: auto;">
                    <table class="table table-striped table-hover text-center align-middle mb-0">
                        <thead class="table-dark" style="position: sticky; top: 0; z-index: 1;">
                            <tr>
                                <th>No</th>
                                <th>ID Kurir</th>
                                <th>Nama Kurir</th>
                                <th>Masuk Cluster</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($hasilClusters as $index => $hasil)
                                @php
                                    $badgeClass = 'bg-secondary';
                                    if($hasil->nilai_cluster == 1) $badgeClass = 'bg-primary';
                                    elseif($hasil->nilai_cluster == 2) $badgeClass = 'bg-success';
                                    elseif($hasil->nilai_cluster == 3) $badgeClass = 'bg-danger';
                                    elseif($hasil->nilai_cluster == 4) $badgeClass = 'bg-warning text-dark';
                                    elseif($hasil->nilai_cluster == 5) $badgeClass = 'bg-info text-dark';
                                @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="fw-bold">{{ $hasil->kurir->id_kurir ?? '-' }}</td>
                                <td class="text-start">{{ $hasil->kurir->nama_kurir ?? '-' }}</td>
                                <td><span class="badge {{ $badgeClass }} px-3 py-2 fs-6 shadow-sm">Cluster {{ $hasil->nilai_cluster }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="py-5 text-muted"><i class="fas fa-folder-open mb-2 fs-3 d-block"></i>Data cluster masih kosong. Silakan hitung K-Means di atas.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- BAGIAN 3: EVALUASI DBI (Tabel Riwayat) -->
<div class="row mb-5">
    <div class="col-md-12">
        <div class="card shadow-sm border-warning">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="fas fa-chart-line text-warning me-2"></i>Evaluasi DBI (Davies-Bouldin Index)</h5>
                
              <form action="{{ route('dbi.run') }}" method="POST">
                    @csrf
                    <!-- Tombol ini akan menghitung seluruh DBI sekaligus -->
                    <button type="submit" class="btn btn-warning fw-bold shadow-sm">
                        <i class="fas fa-calculator me-1"></i> Hitung Semua DBI
                    </button>
                </form>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center align-middle mb-0">
                        <thead class="table-warning">
                            <tr>
                                <th>Jumlah K</th>
                                <th>Jumlah Kurir (Dalam Masing-Masing Cluster)</th>
                                <th>Hasil DBI (Terkecil = Terbaik)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dbiHistory as $h)
                            <tr>
                                <td class="fw-bold fs-5 text-primary">K = {{ $h['k'] }}</td>
                                <td class="fw-bold">
                                    <!-- Memecah string jumlah kurir menjadi badge agar rapi -->
                                    @foreach(explode(' | ', $h['counts']) as $badge)
                                        <span class="badge bg-dark px-2 py-1 me-1 shadow-sm">{{ $badge }}</span>
                                    @endforeach
                                </td>
                                <td class="fw-bold fs-5 {{ $h['dbi'] ? 'text-danger' : 'text-muted' }}">
                                    @if($h['dbi'])
                                        {{ number_format($h['dbi'], 5) }}
                                    @else
                                        <span class="fs-6 fw-normal fst-italic">Belum Dihitung</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="py-4 text-muted"><i class="fas fa-info-circle mb-2 fs-3 d-block"></i>Belum ada proses K-Means yang dijalankan. Lakukan hitung K-Means di atas.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateCentroids() {
        let k = parseInt(document.getElementById('k_value').value);
        for(let i=1; i<=5; i++) {
            let wrapper = document.getElementById('centroid_wrapper_' + i);
            let select = document.getElementById('c_' + i);
            if(i <= k) {
                wrapper.style.display = 'block';
                select.disabled = false;
                select.required = true;
            } else {
                wrapper.style.display = 'none';
                select.disabled = true;
                select.required = false;
            }
        }
    }
    document.addEventListener('DOMContentLoaded', updateCentroids);
</script>
@endsection
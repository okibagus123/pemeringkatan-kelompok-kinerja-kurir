@extends('layouts.app')

@section('content')

<!-- Notifikasi Sukses / Error -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="fas fa-balance-scale text-primary me-2"></i>Tahap 1: Normalisasi Min-Max (Skala 0 - 1)</h5>
        
        <div class="d-flex">
            <!-- Tombol Batalkan Normalisasi (Hanya muncul jika data sudah dinormalisasi) -->
            @if($normalisasi->isNotEmpty())
            <form action="{{ route('normalisasi.cancel') }}" method="POST" class="me-2" onsubmit="return confirm('Yakin ingin membatalkan normalisasi? Ini juga akan mereset hasil K-Means dan TOPSIS.')">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm shadow-sm">
                    <i class="fas fa-undo me-1"></i> Batalkan
                </button>
            </form>
            @endif

            <!-- Tombol Proses Normalisasi -->
            <form action="{{ route('normalisasi.run') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm shadow-sm">
                    <i class="fas fa-calculator me-1"></i> Proses Normalisasi Min-Max
                </button>
            </form>
        </div>
    </div>
    
    <div class="card-body">
        <div class="alert alert-info py-2 mb-3" role="alert">
            <small>
                <i class="fas fa-info-circle me-1"></i> 
                @if($normalisasi->isEmpty())
                    Menampilkan data mentah kurir. Klik tombol <b>"Proses Normalisasi Min-Max"</b> di atas untuk menghitung nilai skala 0-1.
                @else
                    Data diubah menggunakan rumus Min-Max baku: <code>(x - min) / (max - min)</code> sebagai persiapan jarak Euclidean K-Means.
                @endif
            </small>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>ID Kurir</th>
                        <th>Nama Kurir</th>
                       @if($normalisasi->isEmpty())
                            <th>Jumlah Paket</th>
                            <th>Tepat Waktu</th>
                            <th>Telat</th>
                            <th>Gagal</th>
                        @else
                            <th>Jumlah Paket (Norm)</th>
                            <th>Tepat Waktu (Norm)</th>
                            <th>Telat (Norm)</th>
                            <th>Gagal (Norm)</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if($normalisasi->isEmpty())
                        {{-- Tampilkan Data Mentah sebelum diproses --}}
                        @forelse($kurirs as $index => $k)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-bold">{{ $k->id_kurir }}</td>
                            <td class="text-start">{{ $k->nama_kurir }}</td>
                            <td>{{ $k->jumlah_paket }}</td>
                            <td>{{ $k->tepat_waktu }}</td>
                            <td><span class="text-warning fw-bold">{{ $k->telat_terkirim }}</span></td>
                            <td><span class="text-danger fw-bold">{{ $k->gagal_terkirim }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-5 text-muted">
                                <i class="fas fa-folder-open mb-2 fs-3 d-block"></i>
                                Belum ada data kurir. Silakan lakukan import atau input data terlebih dahulu di menu <b>Input Data</b>.
                            </td>
                        </tr>
                        @endforelse
                    @else
                        {{-- Tampilkan Hasil Normalisasi setelah tombol diklik --}}
                        @foreach($normalisasi as $index => $norm)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-bold">{{ $norm->kurir->id_kurir ?? '-' }}</td>
                            <td class="text-start">{{ $norm->kurir->nama_kurir ?? '-' }}</td>
                            <td class="text-primary fw-bold">{{ number_format($norm->jumlah_paket_norm, 4) }}</td>
                            <td class="text-success fw-bold">{{ number_format($norm->tepat_waktu_norm, 4) }}</td>
                            <td class="text-warning fw-bold">{{ number_format($norm->telat_norm, 4) }}</td>
                            <td class="text-danger fw-bold">{{ number_format($norm->gagal_norm, 4) }}</td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')
@section('content')

<div class="row mb-4">
    <div class="col-md-12 text-center">
        <h3 class="fw-bold text-success"><i class="fas fa-trophy me-2"></i>Hasil Akhir Kinerja Kurir</h3>
        <p class="text-muted">Peringkat kinerja didasarkan pada perhitungan kombinasi K-Means & TOPSIS</p>
    </div>
</div>

<div class="card shadow-sm border-success mb-5">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold"><i class="fas fa-list-ol text-success me-2"></i>Pemeringkatan Kurir Berdasarkan Kinerja Kelompok</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="align-middle">Cluster</th>
                        <th class="align-middle">ID Kurir</th>
                        <th class="align-middle text-start">Nama Kurir</th>
                        <th class="align-middle">Jumlah Orderan P (Norm)</th>
                        <th class="align-middle">Paket Tepat Wa (Norm)</th>
                        <th class="align-middle">Paket Telat Diter (Norm)</th>
                        <th class="align-middle">Jumlah Paket Gagal (Norm)</th>
                        <th class="align-middle bg-success text-white">Ranking</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hasilClusters as $cId => $members)
                        @php 
                            $isFirst = true; 
                            
                            // Logika Pewarnaan Setiap Cluster
                            $rowColor = '';
                            if($cId == 1) $rowColor = 'table-primary';         // Biru Lembut
                            elseif($cId == 2) $rowColor = 'table-success';     // Hijau Lembut
                            elseif($cId == 3) $rowColor = 'table-danger';      // Merah Lembut
                            elseif($cId == 4) $rowColor = 'table-warning';     // Kuning Lembut
                            elseif($cId == 5) $rowColor = 'table-info';        // Cyan/Biru Muda Lembut
                        @endphp
                        
                        @foreach($members as $m)
                            @php 
                                $norm = $normalisasi->get($m->kurir_id); 
                                $topsis = $hasilTopsis->get($cId);
                            @endphp
                            
                            <!-- Terapkan warna ke seluruh baris -->
                            <tr class="{{ $rowColor }}">
                                
                                @if($isFirst)
                                    <!-- Menghapus bg-light agar warna cluster terlihat, dan menambah align-middle agar tulisan di tengah persis -->
                                    <td rowspan="{{ count($members) }}" class="fw-bold fs-5 border-end border-3 align-middle">
                                        C{{ $cId }}
                                    </td>
                                @endif
                                
                                <td class="fw-bold">{{ $m->kurir->id_kurir ?? '-' }}</td>
                                <td class="text-start">{{ $m->kurir->nama_kurir ?? '-' }}</td>
                                
                                <td>{{ $norm ? number_format($norm->jumlah_paket_norm, 3) : '-' }}</td>
                                <td>{{ $norm ? number_format($norm->tepat_waktu_norm, 3) : '-' }}</td>
                                <td>{{ $norm ? number_format($norm->telat_norm, 3) : '-' }}</td>
                                <td>{{ $norm ? number_format($norm->gagal_norm, 3) : '-' }}</td>
                                
                                @if($isFirst)
                                    <!-- Jika Ranking 1, background diubah jadi Emas (bg-warning), jika tidak, ikuti warna cluster -->
                                    <td rowspan="{{ count($members) }}" class="fw-bold fs-3 border-start border-3 align-middle {{ $topsis && $topsis->ranking == 1 ? 'bg-warning text-dark' : '' }}">
                                        @if($topsis)
                                            @if($topsis->ranking == 1) <i class="fas fa-crown text-danger fs-4 me-1 d-block mb-1"></i> @endif
                                            {{ $topsis->ranking }}
                                        @else
                                            <span class="badge bg-secondary fs-6">Belum Dinilai</span>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                            
                            @php $isFirst = false; @endphp
                        @endforeach
                    @empty
                    <tr><td colspan="8" class="py-5 text-muted"><i class="fas fa-box-open fs-1 d-block mb-3"></i>Data hasil akhir masih kosong. Silakan jalankan perhitungan K-Means dan TOPSIS.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
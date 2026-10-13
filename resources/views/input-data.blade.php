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

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold"><i class="fas fa-database text-primary me-2"></i>Data Kinerja Kurir Mentah</h5>
        <div>
            <!-- Tombol Hapus Semua -->
            @if($kurirs->count() > 0)
            <form action="{{ route('kurir.deleteAll') }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus SELURUH data kurir? Tindakan ini tidak dapat dibatalkan.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm me-2">
                    <i class="fas fa-trash-alt me-1"></i> Hapus Semua
                </button>
            </form>
            @endif

            <!-- Tombol Trigger Modal Input Manual -->
            <button class="btn btn-outline-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#manualModal">
                <i class="fas fa-plus me-1"></i> Input Manual
            </button>
            
            <!-- Tombol Trigger Modal Import Excel -->
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fas fa-file-excel me-1"></i> Import Excel
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0 text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>ID Kurir</th>
                        <th>Nama Kurir</th>
                        <th>Jumlah Paket (B)</th>
                        <th>Tepat Waktu (B)</th>
                        <th>Telat (C)</th>
                        <th>Gagal (C)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kurirs as $index => $kurir)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="fw-bold">{{ $kurir->id_kurir }}</td>
                        <td class="text-start">{{ $kurir->nama_kurir }}</td>
                        <td>{{ $kurir->jumlah_paket }}</td>
                        <td>{{ $kurir->tepat_waktu }}</td>
                        <td><span class="text-warning fw-bold">{{ $kurir->telat_terkirim }}</span></td>
                        <td><span class="text-danger fw-bold">{{ $kurir->gagal_terkirim }}</span></td>
                        <td>
                            <!-- Tombol Trigger Edit Modal -->
                            <button class="btn btn-warning btn-sm text-white" data-bs-toggle="modal" data-bs-target="#editModal{{ $kurir->id }}" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>

                            <!-- Tombol Hapus Satuan -->
                            <form action="{{ route('kurir.delete', $kurir->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data kurir {{ $kurir->nama_kurir }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="fas fa-folder-open mb-2 fs-3 d-block"></i>
                            Belum ada data kurir. Silakan gunakan tombol <b>Input Manual</b> atau <b>Import Excel</b> di atas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ================= DAFTAR MODAL EDIT (DILETAKKAN DI LUAR TABEL) ================= -->
@foreach($kurirs as $kurir)
<div class="modal fade" id="editModal{{ $kurir->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $kurir->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('kurir.update', $kurir->id) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-header">
            <h5 class="modal-title fw-bold" id="editModalLabel{{ $kurir->id }}"><i class="fas fa-edit text-warning me-2"></i>Edit Data Kurir</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-start">
            <div class="mb-3">
                <label class="form-label fw-bold">ID Kurir</label>
                <input type="text" class="form-control" name="id_kurir" value="{{ $kurir->id_kurir }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Nama Kurir</label>
                <input type="text" class="form-control" name="nama_kurir" value="{{ $kurir->nama_kurir }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Jumlah Paket</label>
                <input type="number" class="form-control" name="jumlah_paket" value="{{ $kurir->jumlah_paket }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Tepat Waktu</label>
                <input type="number" class="form-control" name="tepat_waktu" value="{{ $kurir->tepat_waktu }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Telat Terkirim</label>
                <input type="number" class="form-control" name="telat_terkirim" value="{{ $kurir->telat_terkirim }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Gagal Terkirim</label>
                <input type="number" class="form-control" name="gagal_terkirim" value="{{ $kurir->gagal_terkirim }}" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-warning text-white">Simpan Perubahan</button>
          </div>
      </form>
    </div>
  </div>
</div>
@endforeach
<!-- ================= AKHIR MODAL EDIT ================= -->

<!-- ================= MODAL INPUT MANUAL ================= -->
<div class="modal fade" id="manualModal" tabindex="-1" aria-labelledby="manualModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('kurir.store') }}" method="POST">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title fw-bold" id="manualModalLabel"><i class="fas fa-plus-circle text-primary me-2"></i>Tambah Data Kurir Manual</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-start">
            <div class="mb-3">
                <label class="form-label fw-bold">ID Kurir</label>
                <input type="text" class="form-control" name="id_kurir" placeholder="Contoh: KUR-001" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Nama Kurir</label>
                <input type="text" class="form-control" name="nama_kurir" placeholder="Contoh: Budi Santoso" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Jumlah Paket</label>
                <input type="number" class="form-control" name="jumlah_paket" placeholder="0" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Tepat Waktu</label>
                <input type="number" class="form-control" name="tepat_waktu" placeholder="0" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Telat Terkirim</label>
                <input type="number" class="form-control" name="telat_terkirim" placeholder="0" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Gagal Terkirim</label>
                <input type="number" class="form-control" name="gagal_terkirim" placeholder="0" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Data</button>
          </div>
      </form>
    </div>
  </div>
</div>

<!-- ================= MODAL IMPORT EXCEL ================= -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('kurir.import') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title fw-bold" id="importModalLabel"><i class="fas fa-file-excel text-success me-2"></i>Import Data dari Excel</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-start">
            <div class="mb-3">
                <label for="file" class="form-label fw-bold">Pilih File Excel (.xlsx, .xls, .csv)</label>
                <input class="form-control" type="file" id="file" name="file" required accept=".xlsx, .xls, .csv">
            </div>
            <div class="alert alert-info py-2 mb-0" role="alert">
                <small>
                    <i class="fas fa-info-circle me-1"></i> Pastikan baris pertama Excel Anda memiliki header kolom: 
                    <code>id_kurir</code>, <code>nama_kurir</code>, <code>jumlah_paket</code>, <code>tepat_waktu</code>, <code>telat_terkirim</code>, <code>gagal_terkirim</code>.
                </small>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-success">Upload & Import</button>
          </div>
      </form>
    </div>
  </div>
</div>
@endsection
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NormalisasiKurir extends Model
{
    protected $table = 'normalisasi_kurirs';

    // Tambahkan baris ini untuk mengizinkan penyimpanan data
    protected $fillable = [
        'kurir_id',
        'jumlah_paket_norm',
        'tepat_waktu_norm',
        'telat_norm',
        'gagal_norm',
    ];

    // Relasi ke tabel kurirs
    public function kurir()
    {
        return $this->belongsTo(Kurir::class, 'kurir_id', 'id');
    }
}
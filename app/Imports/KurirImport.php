<?php

namespace App\Imports;

use App\Models\Kurir;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KurirImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Kurir([
            // Mencocokkan berbagai variasi nama kolom dari Excel ke Database
            'id_kurir'       => $row['id_kurir'] ?? $row['id'] ?? null,
            'nama_kurir'     => $row['nama_kurir'] ?? $row['nama'] ?? null,
            
            // Kolom dari file Excel Anda
            'jumlah_paket'   => $row['jumlah_paket'] ?? $row['jumlah_orderan_paket'] ?? 0,
            'tepat_waktu'    => $row['tepat_waktu'] ?? $row['paket_tepat_waktu'] ?? 0,
            'telat_terkirim' => $row['telat_terkirim'] ?? $row['paket_telat_diterima'] ?? 0,
            'gagal_terkirim' => $row['gagal_terkirim'] ?? $row['jumlah_paket_gagal_diterima'] ?? 0,
        ]);
    }
}
<?php

namespace App\Services;

use App\Models\Kurir;
use App\Models\NormalisasiKurir;

class MinMaxNormalizationService
{
    public function normalize()
    {
        $kurirs = Kurir::all();
        if ($kurirs->isEmpty()) return false;

        // Mencari nilai Max dan Min dari masing-masing kolom (kriteria)
        $maxMin = [
            'paket' => ['max' => $kurirs->max('jumlah_paket'), 'min' => $kurirs->min('jumlah_paket')],
            'tepat' => ['max' => $kurirs->max('tepat_waktu'), 'min' => $kurirs->min('tepat_waktu')],
            'telat' => ['max' => $kurirs->max('telat_terkirim'), 'min' => $kurirs->min('telat_terkirim')],
            'gagal' => ['max' => $kurirs->max('gagal_terkirim'), 'min' => $kurirs->min('gagal_terkirim')]
        ];

        NormalisasiKurir::truncate();

        foreach ($kurirs as $k) {
            // Semua kriteria dihitung menggunakan rumus Min-Max dasar: (x - min) / (max - min)
            $paketNorm = $this->calcMinMax($k->jumlah_paket, $maxMin['paket']);
            $tepatNorm = $this->calcMinMax($k->tepat_waktu, $maxMin['tepat']);
            $telatNorm = $this->calcMinMax($k->telat_terkirim, $maxMin['telat']);
            $gagalNorm = $this->calcMinMax($k->gagal_terkirim, $maxMin['gagal']);

            NormalisasiKurir::create([
                'kurir_id' => $k->id,
                'jumlah_paket_norm' => $paketNorm,
                'tepat_waktu_norm' => $tepatNorm,
                'telat_norm' => $telatNorm,
                'gagal_norm' => $gagalNorm,
            ]);
        }
        return true;
    }

    // Rumus Min-Max Tunggal untuk persiapan input K-Means
    private function calcMinMax($val, $range) {
        $divider = ($range['max'] - $range['min']);
        return $divider == 0 ? 0 : ($val - $range['min']) / $divider;
    }
}
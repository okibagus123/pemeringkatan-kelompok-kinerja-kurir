<?php

namespace App\Services;

class TopsisService
{
    public function run($centroids, $bobot, $jenis)
    {
        // 1. Matriks Keputusan Ternormalisasi Berbobot (Normalisasi Min-Max x Bobot)
        $terbobot = [];
        foreach ($centroids as $cId => $c) {
            $terbobot[$cId] = [
                'paket' => $c['paket'] * $bobot['paket'],
                'tepat' => $c['tepat'] * $bobot['tepat'],
                'telat' => $c['telat'] * $bobot['telat'],
                'gagal' => $c['gagal'] * $bobot['gagal'],
            ];
        }

        // 2. Solusi Ideal Positif (A+) & Negatif (A-)
        $kriteria = ['paket', 'tepat', 'telat', 'gagal'];
        $a_plus = [];
        $a_min = [];

        foreach ($kriteria as $k) {
            $values = array_column($terbobot, $k);
            if ($jenis[$k] == 'benefit') {
                $a_plus[$k] = max($values); // Benefit: A+ max, A- min
                $a_min[$k]  = min($values);
            } else {
                $a_plus[$k] = min($values); // Cost: A+ min, A- max
                $a_min[$k]  = max($values);
            }
        }

        // 3. Jarak Solusi Ideal (D+ dan D-) & Nilai Preferensi (RC / V)
        $hasil = [];
        foreach ($terbobot as $cId => $t) {
            $d_plus = 0;
            $d_min = 0;
            foreach ($kriteria as $k) {
                $d_plus += pow($t[$k] - $a_plus[$k], 2);
                $d_min  += pow($t[$k] - $a_min[$k], 2);
            }
            $d_plus = sqrt($d_plus); // Jarak ke A+ (S+)
            $d_min  = sqrt($d_min);  // Jarak ke A- (S-)

            // Rumus Relative Closeness / Nilai V
            $v = ($d_plus + $d_min) == 0 ? 0 : $d_min / ($d_plus + $d_min);
            
            // Logika Penentuan Kategori Kinerja
            $kategori = 'Buruk';
            if ($v >= 0.75) $kategori = 'Sangat Baik';
            elseif ($v >= 0.50) $kategori = 'Baik';
            elseif ($v >= 0.25) $kategori = 'Cukup';

            $hasil[] = [
                'cluster' => $cId,
                'v' => $v,
                'kategori' => $kategori
            ];
        }

        // 4. Ranking (Urutkan dari nilai V terbesar ke terkecil)
        usort($hasil, function($a, $b) {
            return $b['v'] <=> $a['v'];
        });

        return $hasil;
    }
}
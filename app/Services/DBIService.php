<?php

namespace App\Services;

use App\Models\HasilCluster;
use App\Models\NormalisasiKurir;

class DBIService
{
    public function calculateFromDB()
    {
        $hasilClusters = HasilCluster::all()->groupBy('nilai_cluster');
        if ($hasilClusters->isEmpty()) return 0;
        
        $k = $hasilClusters->count();
        $clusters = [];
        $centroids = [];
        
        foreach ($hasilClusters as $cId => $members) {
            $clusterData = [];
            $sum = ['jumlah_paket_norm'=>0, 'tepat_waktu_norm'=>0, 'telat_norm'=>0, 'gagal_norm'=>0];
            foreach ($members as $m) {
                $norm = NormalisasiKurir::where('kurir_id', $m->kurir_id)->first();
                if ($norm) {
                    $row = $norm->toArray();
                    $clusterData[] = $row;
                    $sum['jumlah_paket_norm'] += $row['jumlah_paket_norm'];
                    $sum['tepat_waktu_norm'] += $row['tepat_waktu_norm'];
                    $sum['telat_norm'] += $row['telat_norm'];
                    $sum['gagal_norm'] += $row['gagal_norm'];
                }
            }
            $clusters[$cId] = $clusterData;
            $count = count($clusterData);
            $centroids[$cId] = [
                'jumlah_paket_norm' => $count ? $sum['jumlah_paket_norm'] / $count : 0,
                'tepat_waktu_norm' => $count ? $sum['tepat_waktu_norm'] / $count : 0,
                'telat_norm' => $count ? $sum['telat_norm'] / $count : 0,
                'gagal_norm' => $count ? $sum['gagal_norm'] / $count : 0,
            ];
        }
        return $this->calculate($clusters, $centroids);
    }

    public function calculate($clusters, $centroids)
    {
        $k = count($clusters);
        $dispersions = []; 
        foreach ($clusters as $cId => $clusterData) {
            $sumDist = 0;
            foreach ($clusterData as $row) { $sumDist += $this->distance($row, $centroids[$cId]); }
            $dispersions[$cId] = count($clusterData) > 0 ? $sumDist / count($clusterData) : 0;
        }

        $dbiSum = 0;
        foreach ($centroids as $i => $ci) {
            $maxR = 0;
            foreach ($centroids as $j => $cj) {
                if ($i !== $j) {
                    $mij = $this->distance($ci, $cj);
                    $rij = $mij == 0 ? 0 : ($dispersions[$i] + $dispersions[$j]) / $mij;
                    if ($rij > $maxR) { $maxR = $rij; }
                }
            }
            $dbiSum += $maxR;
        }
        return $k > 0 ? $dbiSum / $k : 0;
    }

    private function distance($a, $b) {
        return sqrt(pow($a['jumlah_paket_norm'] - $b['jumlah_paket_norm'], 2) + pow($a['tepat_waktu_norm'] - $b['tepat_waktu_norm'], 2) + pow($a['telat_norm'] - $b['telat_norm'], 2) + pow($a['gagal_norm'] - $b['gagal_norm'], 2));
    }
}
<?php

namespace App\Services;

class KMeansService
{
   public function run($data, $k, $manualCentroidIds = [], $maxIter = 100)
    {
        $centroids = $this->initCentroidsManual($data, $manualCentroidIds);
        $clusters = [];
        $finalIter = 0;
        
        for ($iter = 1; $iter <= $maxIter; $iter++) {
            $finalIter = $iter;
            $clusters = array_fill(1, $k, []);
            
            foreach ($data as $row) {
                $distances = [];
                foreach ($centroids as $cId => $centroid) {
                    $distances[$cId] = $this->euclideanDistance($row, $centroid);
                }
                $closestK = array_search(min($distances), $distances);
                $clusters[$closestK][] = $row;
            }
            
            $newCentroids = [];
            $changed = false;
            foreach ($clusters as $cId => $clusterData) {
                $newCentroids[$cId] = $this->calculateMean($clusterData);
                
                if (
                    abs($newCentroids[$cId]['jumlah_paket_norm'] - $centroids[$cId]['jumlah_paket_norm']) > 0.0001 ||
                    abs($newCentroids[$cId]['tepat_waktu_norm'] - $centroids[$cId]['tepat_waktu_norm']) > 0.0001 ||
                    abs($newCentroids[$cId]['telat_norm'] - $centroids[$cId]['telat_norm']) > 0.0001 ||
                    abs($newCentroids[$cId]['gagal_norm'] - $centroids[$cId]['gagal_norm']) > 0.0001
                ) {
                    $changed = true;
                }
            }
            $centroids = $newCentroids;
            if (!$changed) break;
        }

        return ['clusters' => $clusters, 'centroids' => $centroids, 'iterations' => $finalIter];
    }
    private function euclideanDistance($a, $b) {
        return sqrt(pow($a['jumlah_paket_norm'] - $b['jumlah_paket_norm'], 2) + pow($a['tepat_waktu_norm'] - $b['tepat_waktu_norm'], 2) + pow($a['telat_norm'] - $b['telat_norm'], 2) + pow($a['gagal_norm'] - $b['gagal_norm'], 2));
    }

    private function initCentroidsManual($data, $manualCentroidIds) {
        $centroids = [];
        $index = 1;
        foreach ($manualCentroidIds as $kurirId) {
            foreach ($data as $row) {
                if ($row['kurir_id'] == $kurirId) {
                    $centroids[$index] = $row;
                    $index++;
                    break;
                }
            }
        }
        return $centroids;
    }

    private function calculateMean($clusterData) {
        if (empty($clusterData)) return ['jumlah_paket_norm'=>0, 'tepat_waktu_norm'=>0, 'telat_norm'=>0, 'gagal_norm'=>0];
        $count = count($clusterData);
        $sum = ['jumlah_paket_norm' => 0, 'tepat_waktu_norm' => 0, 'telat_norm' => 0, 'gagal_norm' => 0];
        foreach ($clusterData as $row) {
            foreach ($sum as $key => $val) { $sum[$key] += $row[$key]; }
        }
        foreach ($sum as $key => $val) { $sum[$key] = $sum[$key] / $count; }
        return $sum;
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kurir;
use App\Models\NormalisasiKurir;
use App\Models\EvaluasiDbi;
use App\Models\HasilTopsis;
use App\Models\HasilCluster;
use App\Services\MinMaxNormalizationService;
use App\Services\KMeansService;
use App\Services\DBIService;
use App\Services\TopsisService;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\KurirImport;
use Illuminate\Support\Facades\Schema;

class SpkController extends Controller
{
    // 1. Menampilkan Halaman Dashboard
    public function dashboard()
    {
        $totalKurir = Kurir::count();
        $totalPaket = Kurir::sum('jumlah_paket');
        
        $bestK = EvaluasiDbi::where('is_best', true)->value('jumlah_cluster') ?? '-';

        return view('dashboard', compact('totalKurir', 'totalPaket', 'bestK'));
    }

    // 2. Menampilkan Halaman Input Data
    public function inputData()
    {
        $kurirs = Kurir::all();
        return view('input-data', compact('kurirs'));
    }

    public function storeKurir(Request $request)
    {
        $request->validate([
            'id_kurir'       => 'required|unique:kurirs,id_kurir',
            'nama_kurir'     => 'required|string|max:255',
            'jumlah_paket'   => 'required|integer|min:0',
            'tepat_waktu'    => 'required|integer|min:0',
            'telat_terkirim' => 'required|integer|min:0',
            'gagal_terkirim' => 'required|integer|min:0',
        ]);

        Kurir::create($request->all());
        return redirect()->route('input.data')->with('success', 'Data kurir berhasil ditambahkan secara manual!');
    }

    public function updateKurir(Request $request, $id)
    {
        $kurir = Kurir::findOrFail($id);

        $request->validate([
            'id_kurir'       => 'required|unique:kurirs,id_kurir,' . $kurir->id,
            'nama_kurir'     => 'required|string|max:255',
            'jumlah_paket'   => 'required|integer|min:0',
            'tepat_waktu'    => 'required|integer|min:0',
            'telat_terkirim' => 'required|integer|min:0',
            'gagal_terkirim' => 'required|integer|min:0',
        ]);

        $kurir->update($request->all());
        return redirect()->route('input.data')->with('success', 'Data kurir berhasil diperbarui!');
    }

    public function destroyKurir($id)
    {
        $kurir = Kurir::findOrFail($id);
        $kurir->delete();
        return redirect()->route('input.data')->with('success', 'Data kurir berhasil dihapus!');
    }

    public function destroyAllKurir()
    {
        Schema::disableForeignKeyConstraints();
        HasilCluster::truncate();
        NormalisasiKurir::truncate();
        EvaluasiDbi::truncate();
        HasilTopsis::truncate();
        Kurir::truncate();
        Schema::enableForeignKeyConstraints();

        return redirect()->route('input.data')->with('success', 'Semua data kurir dan hasil analisis berhasil dikosongkan!');
    }

    public function importKurir(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv|max:2048']);

        try {
            Excel::import(new KurirImport, $request->file('file'));
            return redirect()->route('input.data')->with('success', 'Data kurir berhasil diimport dari Excel!');
        } catch (\Exception $e) {
            return redirect()->route('input.data')->with('error', 'Terjadi kesalahan format file: ' . $e->getMessage());
        }
    }

    // 3. Menampilkan Halaman Normalisasi
    public function normalisasi()
    {
        $kurirs = Kurir::all();
        $normalisasi = NormalisasiKurir::with('kurir')->get(); 
        
        return view('normalisasi', compact('kurirs', 'normalisasi'));
    }
    
    public function runNormalisasi(MinMaxNormalizationService $normService)
    {
        $check = Kurir::count();
        if ($check == 0) return redirect()->route('input.data')->with('error', 'Data kurir masih kosong. Harap input data terlebih dahulu.');

        $normService->normalize();
        return redirect()->route('normalisasi')->with('success', 'Normalisasi Min-Max berhasil dijalankan!');
    }

    public function cancelNormalisasi()
    {
        Schema::disableForeignKeyConstraints();
        NormalisasiKurir::truncate();
        HasilCluster::truncate();    
        EvaluasiDbi::truncate();      
        HasilTopsis::truncate();      
        Schema::enableForeignKeyConstraints();

        return redirect()->route('normalisasi')->with('success', 'Normalisasi dibatalkan. Data direset.');
    }

    // 4. Menampilkan Halaman K-Means & Evaluasi DBI
    public function kmeans()
    {
        $normalisasi = NormalisasiKurir::with('kurir')->get(); 
        
        // Ambil riwayat K-Means dari session
        $dbiHistory = session('dbi_history', []); 
        ksort($dbiHistory);
        
        // Tampilkan hanya K yang terakhir kali diklik agar tabel rapi
        $lastK = session('last_k', HasilCluster::max('jumlah_cluster') ?? 3);
        $hasilClusters = HasilCluster::with('kurir')->where('jumlah_cluster', $lastK)->orderBy('nilai_cluster', 'asc')->get(); 
        
        $clusterCounts = $hasilClusters->groupBy('nilai_cluster')->map->count();
        $totalK = $clusterCounts->count();
        $iterations = session('kmeans_iterations', 0); 

        return view('kmeans', compact('normalisasi', 'hasilClusters', 'clusterCounts', 'totalK', 'iterations', 'dbiHistory', 'lastK'));
    }

    public function runKMeans(Request $request, KMeansService $kmeansService, DBIService $dbiService)
    {
        $request->validate([
            'k_value' => 'required|integer|in:3,4,5',
            'centroid' => 'required|array',
            'centroid.*' => 'required|exists:kurirs,id'
        ]);

        $k = $request->k_value;
        $manualCentroidIds = $request->centroid;

        if (count(array_unique($manualCentroidIds)) !== count($manualCentroidIds)) {
            return back()->withInput()->with('error', 'Centroid awal tidak boleh sama (duplikat).');
        }

        $data = NormalisasiKurir::all()->toArray();
        if (empty($data)) return back()->withInput()->with('error', 'Data normalisasi kosong.');

        $kmeansResult = $kmeansService->run($data, $k, $manualCentroidIds);
        session(['kmeans_iterations' => $kmeansResult['iterations']]);
        session(['last_k' => $k]); // Simpan memori K terakhir yang diklik

        $calculatedDbi = $dbiService->calculate($kmeansResult['clusters'], $kmeansResult['centroids']);

        // HAPUS HANYA UNTUK K INI, BUKAN TRUNCATE SEMUA
        HasilCluster::where('jumlah_cluster', $k)->delete();

        $countsDetail = [];
        foreach ($kmeansResult['clusters'] as $cId => $members) {
            $countsDetail[] = "Cluster {$cId}: " . count($members) . " Kurir";
            foreach ($members as $m) {
                HasilCluster::create([
                    'kurir_id' => $m['kurir_id'],
                    'jumlah_cluster' => $k,
                    'nilai_cluster' => $cId
                ]);
            }
        }

        $history = session('dbi_history', []);
        $history[$k] = [
            'k' => $k,
            'counts' => implode(' | ', $countsDetail),
            'dbi_asli' => $calculatedDbi, 
            'dbi' => null 
        ];
        session(['dbi_history' => $history]);

        return redirect()->route('kmeans')->withInput()->with('success', "Proses K-Means K={$k} berhasil!");
    }

    public function runDBI()
    {
        $history = session('dbi_history', []);
        if (empty($history)) return back()->with('error', 'Belum ada data K-Means yang dijalankan!');

        Schema::disableForeignKeyConstraints();
        EvaluasiDbi::truncate();
        Schema::enableForeignKeyConstraints();

        $bestK = null;
        $minDbi = 9999;

        foreach ($history as $key => $h) {
            if (isset($h['dbi_asli'])) {
                $history[$key]['dbi'] = $h['dbi_asli'];
                if ($h['dbi_asli'] < $minDbi) {
                    $minDbi = $h['dbi_asli'];
                    $bestK = $h['k'];
                }
            }
        }

        foreach ($history as $key => $h) {
            if ($h['dbi'] !== null) {
                EvaluasiDbi::create([
                    'jumlah_cluster' => $h['k'],
                    'nilai_dbi' => $h['dbi'],
                    'is_best' => ($h['k'] == $bestK)
                ]);
            }
        }

        session(['dbi_history' => $history]);
        return redirect()->route('kmeans')->with('success', "Evaluasi DBI dihitung. K={$bestK} adalah yang terbaik!");
    }

    // --- AREA TOPSIS ---
    public function topsis()
    {
        // Cari K terbaik yang resmi dari Evaluasi DBI
        $bestDbi = EvaluasiDbi::where('is_best', 1)->first();
        $bestK = $bestDbi ? $bestDbi->jumlah_cluster : (HasilCluster::max('jumlah_cluster') ?? 4); 

        // AMBIL HANYA DATA DARI K TERBAIK
        $hasilClusters = HasilCluster::where('jumlah_cluster', $bestK)->get()->groupBy('nilai_cluster');
        
        $centroids = [];
        
        foreach ($hasilClusters as $cId => $members) {
            $sum = ['paket' => 0, 'tepat' => 0, 'telat' => 0, 'gagal' => 0];
            $count = count($members);
            foreach ($members as $m) {
                $norm = NormalisasiKurir::where('kurir_id', $m->kurir_id)->first();
                if ($norm) {
                    $sum['paket'] += $norm->jumlah_paket_norm;
                    $sum['tepat'] += $norm->tepat_waktu_norm;
                    $sum['telat'] += $norm->telat_norm;
                    $sum['gagal'] += $norm->gagal_norm;
                }
            }
            if ($count > 0) {
                $centroids[$cId] = [
                    'paket' => $sum['paket'] / $count,
                    'tepat' => $sum['tepat'] / $count,
                    'telat' => $sum['telat'] / $count,
                    'gagal' => $sum['gagal'] / $count,
                ];
            }
        }

        $hasilTopsis = HasilTopsis::orderBy('ranking', 'asc')->get();

        return view('topsis', compact('centroids', 'hasilTopsis', 'bestK', 'bestDbi'));
    }

    public function runTopsis(Request $request, TopsisService $topsisService)
    {
        $request->validate([
            'bobot_paket' => 'required|numeric', 'jenis_paket' => 'required|in:benefit,cost',
            'bobot_tepat' => 'required|numeric', 'jenis_tepat' => 'required|in:benefit,cost',
            'bobot_telat' => 'required|numeric', 'jenis_telat' => 'required|in:benefit,cost',
            'bobot_gagal' => 'required|numeric', 'jenis_gagal' => 'required|in:benefit,cost',
        ]);

        $bobot = ['paket' => $request->bobot_paket, 'tepat' => $request->bobot_tepat, 'telat' => $request->bobot_telat, 'gagal' => $request->bobot_gagal];
        $jenis = ['paket' => $request->jenis_paket, 'tepat' => $request->jenis_tepat, 'telat' => $request->jenis_telat, 'gagal' => $request->jenis_gagal];

        // WAJIB difilter berdasarkan K terbaik
        $bestDbi = EvaluasiDbi::where('is_best', 1)->first();
        $bestK = $bestDbi ? $bestDbi->jumlah_cluster : (HasilCluster::max('jumlah_cluster') ?? 4); 

        $hasilClusters = HasilCluster::where('jumlah_cluster', $bestK)->get()->groupBy('nilai_cluster');
        
        $centroids = [];
        foreach ($hasilClusters as $cId => $members) {
            $sum = ['paket' => 0, 'tepat' => 0, 'telat' => 0, 'gagal' => 0];
            foreach ($members as $m) {
                $norm = NormalisasiKurir::where('kurir_id', $m->kurir_id)->first();
                if ($norm) {
                    $sum['paket'] += $norm->jumlah_paket_norm; $sum['tepat'] += $norm->tepat_waktu_norm;
                    $sum['telat'] += $norm->telat_norm; $sum['gagal'] += $norm->gagal_norm;
                }
            }
            if (count($members) > 0) {
                $centroids[$cId] = ['paket' => $sum['paket'] / count($members), 'tepat' => $sum['tepat'] / count($members), 'telat' => $sum['telat'] / count($members), 'gagal' => $sum['gagal'] / count($members)];
            }
        }

        if (empty($centroids)) return back()->with('error', 'Pusat Centroid kosong. Jalankan K-Means terlebih dahulu!');

        $hasil = $topsisService->run($centroids, $bobot, $jenis);

        Schema::disableForeignKeyConstraints();
        HasilTopsis::truncate();
        Schema::enableForeignKeyConstraints();

      foreach ($hasil as $index => $h) {
            HasilTopsis::create([
                'cluster' => $h['cluster'],           
                'nilai_preferensi' => $h['v'],        
                'kategori' => $h['kategori'],
                'ranking' => $index + 1
            ]);
        }

        return redirect()->route('topsis')->with('success', 'Perhitungan Ranking TOPSIS berhasil dilakukan!');
    }

    // 6. Menampilkan Halaman Hasil Akhir
    public function hasilAkhir()
    {
        $bestDbi = EvaluasiDbi::where('is_best', 1)->first();
        $bestK = $bestDbi ? $bestDbi->jumlah_cluster : (HasilCluster::max('jumlah_cluster') ?? 4); 

        // Filter tabel agar rapi hanya menampilkan cluster K terbaik
        $hasilClusters = HasilCluster::with('kurir')
                            ->where('jumlah_cluster', $bestK)
                            ->orderBy('nilai_cluster', 'asc')
                            ->get()
                            ->groupBy('nilai_cluster');
        
        $normalisasi = NormalisasiKurir::all()->keyBy('kurir_id');
        $hasilTopsis = HasilTopsis::all()->keyBy('cluster');

        return view('hasil-akhir', compact('hasilClusters', 'normalisasi', 'hasilTopsis'));
    }
}
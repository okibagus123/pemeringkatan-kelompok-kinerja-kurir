<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilCluster extends Model
{
    use HasFactory;

    protected $fillable = [
        'kurir_id',
        'jumlah_cluster',
        'nilai_cluster',
    ];

    // Relasi ke tabel kurirs
    public function kurir()
    {
        return $this->belongsTo(Kurir::class, 'kurir_id', 'id');
    }
}
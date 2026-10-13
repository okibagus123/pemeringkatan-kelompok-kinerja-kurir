<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kurir extends Model
{
    protected $table = 'kurirs';

    protected $fillable = [
        'id_kurir',
        'nama_kurir',
        'jumlah_paket',
        'tepat_waktu',
        'telat_terkirim',
        'gagal_terkirim',
    ];
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluasiDbi extends Model
{
    protected $table = 'evaluasi_dbis';

    protected $fillable = [
        'jumlah_cluster',
        'nilai_dbi',
        'is_best',
    ];
}
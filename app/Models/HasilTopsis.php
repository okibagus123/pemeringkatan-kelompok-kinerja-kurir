<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilTopsis extends Model
{
    protected $table = 'hasil_topsis';

    protected $fillable = [
        'cluster',
        'nilai_preferensi',
        'ranking',
        'kategori',
    ];
}
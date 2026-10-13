<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */public function up(): void
{
    Schema::create('normalisasi_kurirs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('kurir_id')->constrained('kurirs')->cascadeOnDelete();
        $table->float('jumlah_paket_norm', 8, 4);
        $table->float('tepat_waktu_norm', 8, 4);
        $table->float('telat_norm', 8, 4);
        $table->float('gagal_norm', 8, 4);
        $table->timestamps();
    });
}};

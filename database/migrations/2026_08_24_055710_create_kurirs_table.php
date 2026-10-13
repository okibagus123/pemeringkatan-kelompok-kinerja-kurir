<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
   Schema::create('kurirs', function (Blueprint $table) {
    $table->id();
    $table->string('id_kurir')->unique();
    $table->string('nama_kurir');
    $table->integer('jumlah_paket');
    $table->integer('tepat_waktu');
    $table->integer('telat_terkirim');
    $table->integer('gagal_terkirim');
    $table->timestamps();
});
}}
;

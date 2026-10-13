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
    Schema::create('hasil_topsis', function (Blueprint $table) {
        $table->id();
        $table->integer('cluster');
        $table->float('nilai_preferensi', 8, 4);
        $table->integer('ranking');
        $table->string('kategori');
        $table->timestamps();
    });
}
};

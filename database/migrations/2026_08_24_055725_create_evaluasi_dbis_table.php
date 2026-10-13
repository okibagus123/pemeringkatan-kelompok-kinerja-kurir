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
    Schema::create('evaluasi_dbis', function (Blueprint $table) {
        $table->id();
        $table->integer('jumlah_cluster');
        $table->float('nilai_dbi', 8, 4);
        $table->boolean('is_best')->default(false);
        $table->timestamps();
    });
}
};

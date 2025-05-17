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
        //
        Schema::create('identitas_rm_master', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bahan')->nullable();
            $table->date('tanggal_kedatangan')->nullable();
            $table->string('suplier_manufactur')->nullable();
            $table->string('asal_bahan')->nullable();
            $table->string('no_mobil', 100)->nullable();
            $table->string('no_spb', 100)->nullable();
            $table->integer('jumlah_kedatangan')->nullable();
            $table->string('lot_batch')->nullable();
            $table->string('jenis_gula', 50)->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('identitas_rm_master');
    }
};

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
        Schema::create('monitoring_storage_before_uses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('production_batch_id');
            $table->string('batch_range');
            $table->integer('nomor_blending');
            $table->float('volume_blending');
            $table->dateTime('waktu_sample')->nullable();
            $table->dateTime('waktu_selesai_pemakaian')->nullable();
            $table->date('estimasi_kadaluarsa')->nullable();
            $table->float('visco')->nullable();
            $table->float('brix')->nullable();
            $table->float('aw')->nullable();
            $table->string('hasil')->nullable();
            $table->tinyInteger('revisi')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_storage_before_uses');
    }
};

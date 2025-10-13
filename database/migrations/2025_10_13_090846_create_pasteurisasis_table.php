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
        Schema::create('monitoring_pasteurisasi', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('production_batch_id')->unsigned();
            $table->string('batch_range')->nullable();
            $table->integer('nomor_pasteurisasi')->nullable();
            $table->float('volume_pasteurisasi')->nullable();
            $table->string('storage')->nullable();
            $table->enum('disposition', ['Release', 'Release Bersyarat', 'Resampling', 'Reject', 'Repro', 'Adjustment', 'Jalan Bareng', 'Leveling'])->nullable();
            $table->float('adjustment_qty_air')->nullable();
            $table->float('adjustment_qty_garam')->nullable();
            $table->float('adjustment_qty_gula')->nullable();
            $table->text('disposition_remaks')->nullable();
            $table->string('revisi')->nullable();
            $table->tinyInteger('is_adjustment')->default(0);
            $table->tinyInteger('not_standard')->default(0);
            $table->string('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasteurisasis');
    }
};

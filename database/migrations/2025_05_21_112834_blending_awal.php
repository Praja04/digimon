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
        //
        Schema::create('blending_awal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_batch_id')->constrained('production_batches')->onDelete('cascade');
            $table->string('batch_range', 50)->nullable();
            $table->integer('nomor_blending')->nullable();
            $table->decimalr('volume_blending', 5, 2)->nullable();
            $table->decimal('brix', 5, 2)->nullable();
            $table->decimal('nacl', 5, 2)->nullable();
            $table->decimal('bj', 5, 2)->nullable();
            $table->decimal('visco', 5, 2)->nullable();
            $table->decimal('aw', 5, 2)->nullable();
            $table->decimal('buih', 5, 2)->nullable();
            $table->string('organo')->nullable();
            $table->decimal('ph', 5, 2)->nullable();
            $table->string('endapan')->nullable();
            $table->string('warna')->nullable();
            $table->string('storage')->nullable();
            $table->enum('disposition', ['Release', 'Release Bersyarat', 'Resampling', 'Reject', 'Repro', 'Adjustment'])->nullable();
            $table->integer('adjusment_qty')->nullable();
            $table->text('disposition_remarks')->nullable();
            $table->string('revisi')->nullable();
            $table->boolean('is_adjustment')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('blending_awal');
        Schema::table('blending_awal', function (Blueprint $table) {
            $table->dropForeign(['production_batch_id']);
        });
    }
};

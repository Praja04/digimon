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
        Schema::create('blending_after_adjust_batch_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blending_awal_id')->constrained('blending_awal')->onDelete('cascade');
            $table->integer('batch_id'); // batch tambahan (misal batch 3)
            $table->integer('production_batch_id'); // batch tambahan (misal batch 3)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('blending_after_adjust_batch_relations');
    }
};

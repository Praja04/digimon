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
        Schema::create('monitoring_pasteurisasi_relations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('monitoring_pasteurisasi_id');
            $table->string('batch');
            $table->bigInteger('production_batch_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_pasterisasi_relations');
    }
};

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
        Schema::create('monitoring_pasteurisasi_data', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('monitoring_pasteurisasi_id')->unsigned();
            $table->foreign('monitoring_pasteurisasi_id')->references('id')->on('monitoring_pasteurisasi');
            $table->float('brix')->nullable();
            $table->float('nacl')->nullable();
            $table->float('bj')->nullable();
            $table->float('visco')->nullable();
            $table->float('aw')->nullable();
            $table->float('buih')->nullable();
            $table->float('ph')->nullable();
            $table->string('organo')->nullable();
            $table->string('endapan')->nullable();
            $table->string('warna')->nullable();
            $table->string('shift')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasteurisasi_data');
    }
};

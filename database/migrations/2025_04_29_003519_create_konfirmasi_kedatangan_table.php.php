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
        Schema::create('konfirmasi_rm', function (Blueprint $table) {
            $table->id();
            $table->integer('id_identitas');
            $table->dateTime('jam_kedatangan');
            $table->dateTime('jam_analisa');
            $table->string('diterima_by_user');
            $table->string('dianalisa_by_user');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('konfirmasi_rm');
    }
};

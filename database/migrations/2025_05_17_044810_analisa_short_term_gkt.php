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
        Schema::create('analisa_short_term_gkt', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_identitas')->constrained('identitas_rm_master')->onDelete('cascade');
            $table->float('brix')->nullable();
            $table->float('ph')->nullable();
            $table->string('kotoran')->nullable();
            $table->string('ka')->nullable();
            $table->string('organo')->nullable();
            $table->string('warna')->nullable();
            $table->string('aroma')->nullable();
            $table->foreignId('id_disposisi')->nullable()->constrained('disposisi_rm');
            $table->string('created_by_user', 50)->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('analisa_short_term_gkt');
    }
};

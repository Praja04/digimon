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
        Schema::create('analisa_garam_gula', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_identitas')->constrained('identitas_rm_master')->onDelete('cascade');
            $table->string('fisik')->nullable();
            $table->string('%ka', 50)->nullable();
            $table->string('kotoran')->nullable();
            $table->string('organo')->nullable();
            $table->string('warna')->nullable();
            $table->string('aroma')->nullable();
            $table->string('%nacl', 50)->nullable();
            $table->float('gross_weight')->nullable();
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
        Schema::dropIfExists('analisa_garam_gula');
    }
};

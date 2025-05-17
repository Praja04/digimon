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
        Schema::create('analisa_long_term_gkt', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_identitas')->constrained('identitas_rm_master')->onDelete('cascade');
            $table->string('uji_kristal')->nullable();
            $table->string('disposisi', 100)->nullable();
            $table->string('created_by_user', 50)->nullable();
            $table->string('attachment')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('analisa_long_term_gkt');
    }
};

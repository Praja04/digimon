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
        Schema::create('gga_ggas_processes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_batch_id')->constrained('production_batches')->onDelete('cascade'); // Referensi ke production_batches
            $table->enum('sample_type', ['GGA', 'GGAS']);
            $table->string('dissolver_number');
            $table->string('barcode')->unique(); // Barcode
            $table->float('result_analysis')->nullable(); // Hasil analisis
            $table->enum('disposition', ['Release', 'Release Bersyarat', 'Resampling', 'Reject', 'Adjustment'])->nullable();
            $table->text('disposition_remarks')->nullable(); // Keterangan untuk disposisi (misalnya jika 'Release Bersyarat')
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gga_ggas_processes');
    }
};

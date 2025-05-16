<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGgaGgasProcessesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gga_processes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('production_batch_id')
                ->constrained('production_batches')
                ->onDelete('cascade');
                
            $table->string('dissolver_number');
            $table->string('barcode')->unique();

            $table->decimal('brix', 5, 2)->nullable();
            $table->decimal('nacl', 5, 2)->nullable();
            $table->decimal('warna', 5, 2)->nullable();

            $table->enum('disposition', [
                'Release',
                'Release Bersyarat',
                'Resampling',
                'Reject',
                'Adjustment'
            ])->nullable();

            $table->text('disposition_remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gga_processes');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GgaGgasProcess extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_batch_id',
        'sample_type',
        'dissolver_number',
        'barcode',
        'result_analysis',
        'disposition',
        'disposition_remarks',
    ];

    // Relasi dengan tabel ProductionBatch
    public function productionBatch()
    {
        return $this->belongsTo(ProductionBatch::class);
    }
}

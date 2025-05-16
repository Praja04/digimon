<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GgaProcess extends Model
{
    use HasFactory;
    protected $table = 'gga_processes';
    protected $fillable = [
        'production_batch_id',
        'batch_range',
        'dissolver_number',
        'barcode',
        'adjusment_qty',
        'brix',
        'nacl',
        'warna',
        'disposition',
        'disposition_remarks',
        'is_adjustment',
        'revisi',
    ];

    // Relasi dengan tabel ProductionBatch
    public function productionBatch()
    {
        return $this->belongsTo(ProductionBatch::class);
    }
    
}

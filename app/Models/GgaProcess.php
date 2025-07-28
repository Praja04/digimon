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
        'batch_number',
        'dissolver_number',
        'barcode',
        'adjustment_qty_air',
        'adjustment_qty_garam',
        'adjustment_qty_gula',
        'brix',
        'nacl',
        'warna',
        'disposition',
        'disposition_remarks',
        'not_standar',
        'revisi',
    ];

    // Relasi dengan tabel ProductionBatch
    public function productionBatch()
    {
        return $this->belongsTo(ProductionBatch::class);
    }
    
}

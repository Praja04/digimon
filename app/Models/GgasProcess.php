<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GgasProcess extends Model
{
    //
    
    use HasFactory;
    protected $table = 'ggas_processes';
    protected $fillable = [
        'production_batch_id',
        'batch_number',
        'dissolver_number',
       'revisi',
        'brix',
        'nacl',
        'warna',
        'disposition',
        'disposition_remarks',
        'adjustment_qty_air',
        'adjustment_qty_garam',
        'adjustment_qty_gula',
        'not_standar',
        'created_by',
    ];
  
    // Relasi dengan tabel ProductionBatch
    public function productionBatch()
    {
        return $this->belongsTo(ProductionBatch::class);
    }
}

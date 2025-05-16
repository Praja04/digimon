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
        'batch_range',
        'dissolver_number',
       'revisi',
        'brix',
        'nacl',
        'warna',
        'disposition',
        'disposition_remarks',
    ];
    public $timestamps = false;

    // Relasi dengan tabel ProductionBatch
    public function productionBatch()
    {
        return $this->belongsTo(ProductionBatch::class);
    }
}

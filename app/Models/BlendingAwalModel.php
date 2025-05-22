<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlendingAwalModel extends Model
{
    //
    use HasFactory;
    protected $table = 'blending_awal';
    protected $fillable = [
        'production_batch_id',
        'batch_range',
        'nomor_blending',
        'volume',
        'brix',
        'nacl',
        'bj',
        'visco',
        'aw',
        'buih',
        'organo',
        'ph',
        'endapan',
        'warna',
        'disposition',
        'disposition_remarks',
        'adjusment_qty',
        'is_adjustment',
        'revisi',
    ];

    public function productionBatch()
    {
        return $this->belongsTo(ProductionBatch::class);
    }
    
}

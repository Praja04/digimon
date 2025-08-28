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
        'adjustment_qty_air',
        'adjustment_qty_garam',
        'adjustment_qty_gula',
        'is_adjustment',
        'revisi',
        'not_standar',
        'created_by',
    ];

    public function productionBatch()
    {
        return $this->belongsTo(ProductionBatch::class);
    }

    public function additionalBatches()
    {
        return $this->hasMany(BlendingBatchRelation::class, 'blending_awal_id');
    }
    

}

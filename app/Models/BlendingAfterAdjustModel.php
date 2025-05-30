<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlendingAfterAdjustModel extends Model
{
    //
    use HasFactory;
    protected $table = 'blending_adjust';
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
        'not_standar'
    ];

    public function productionBatch()
    {
        return $this->belongsTo(ProductionBatch::class);
    }

    public function additionalBatches()
    {
        return $this->hasMany(BlendingAfterAdjustBatchRelation::class, 'blending_after_adjust_id');
    }
}

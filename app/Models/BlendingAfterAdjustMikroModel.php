<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlendingAfterAdjustMikroModel extends Model
{
    //
    use HasFactory;
    protected $table = 'blending_after_adjust_mikro';
    protected $fillable = [
        'production_batch_id',
        'batch_range',
        'nomor_blending',
        'volume_blending',
        'eb',
        'tpc',
        'ym',
        'hasil',
    ];

    public function productionBatch()
    {
        return $this->belongsTo(ProductionBatch::class);
    }
}

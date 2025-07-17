<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonitoringStorageMikroModel extends Model
{
    //
    protected $table = 'monitoring_storage_mikro';
    protected $fillable = [
        'production_batch_id',
        'batch_range',
        'nomor_blending',
        'volume_blending',
        'eb',
        'tpc',
        'ym',
        'hasil',
        'revisi'
    ];

    public function productionBatch()
    {
        return $this->belongsTo(ProductionBatch::class);
    }
}


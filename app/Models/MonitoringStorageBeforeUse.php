<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonitoringStorageBeforeUse extends Model
{
    protected $fillable = [
        'production_batch_id',
        'batch_range',
        'nomor_blending',
        'volume_blending',
        'waktu_sample',
        'waktu_selesai_pemakaian',
        'estimasi_kadaluarsa',
        'visco',
        'brix',
        'aw',
        'hasil',
        'revisi',
    ];

    public function ProductionBatch()
    {
        return $this->belongsTo(ProductionBatch::class);
    }
}

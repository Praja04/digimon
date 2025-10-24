<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonitoringStorageBeforeUse extends Model
{
    protected $fillable = [
        'production_batch_id',
        'nomor_blending',
        'volume',
        'batch_range',
        'storage',
        'jenis_sample',
        'waktu_sample',
        'waktu_selesai_pemakaian',
        'estimasi_kadaluarsa',
        'visco',
        'brix',
        'aw',
        'hasil',
    ];

    public function ProductionBatch()
    {
        return $this->belongsTo(ProductionBatch::class);
    }
}

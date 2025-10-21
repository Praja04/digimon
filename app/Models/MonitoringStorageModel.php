<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonitoringStorageModel extends Model
{
    //
    use HasFactory;
    protected $table = 'monitoring_storage';
    protected $fillable = [
        'production_batch_id',
        'batch_range',
        'nomor_blending',
        'volume_blending',
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
        'storage',
        'disposition',
        'disposition_remarks',
        'adjusment_qty',
        'is_adjustment',
        'production_time',
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
        return $this->hasMany(MonitoringStorageRelation::class, 'monitoring_storage_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonitoringPasteurisasi extends Model
{
    protected $table = 'monitoring_pasteurisasi';

    protected $fillable = [
        'production_batch_id',
        'batch_range',
        'nomor_pasteurisasi',
        'volume_pasteurisasi',
        'storage',
        'disposition',
        'adjustment_qty_air',
        'adjustment_qty_garam',
        'adjustment_qty_gula',
        'disposition_remaks',
        'revisi',
        'is_adjustment',
        'not_standard',
        'created_by',
    ];

    public function productionBatch()
    {
        return $this->belongsTo(ProductionBatch::class);
    }

    public function monitoringPasteurisasiData()
    {
        return $this->hasMany(MonitoringPasteurisasiData::class);
    }

    public function MonitoringPasterisasiRelation()
    {
        return $this->hasMany(MonitoringPasteurisasiRelation::class);
    }

    public function additionalBatches()
    {
        return $this->hasMany(MonitoringPasteurisasiRelation::class, 'monitoring_pasteurisasi_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonitoringTurunBlending extends Model
{
    //
    use HasFactory;
    protected $table = 'monitoring_turun_blending';
    protected $fillable = [
        'production_batch_id',
        'batch_range',
        'nomor_blending',
        'volume',
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
    public function monitoringData()
    {
        return $this->hasMany(MonitoringTurunBlendingData::class, 'monitoring_turun_blending_id');
    }

    public function productionBatch()
    {
        return $this->belongsTo(ProductionBatch::class);
    }

    public function additionalBatches()
    {
        return $this->hasMany(MonitoringTurunBlendingRelation::class, 'monitoring_turun_blending_id');
    }

   
}

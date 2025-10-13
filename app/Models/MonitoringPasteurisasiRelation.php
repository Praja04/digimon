<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonitoringPasteurisasiRelation extends Model
{
    protected $table = 'monitoring_pasteurisasi_relations';

    protected $fillable = [
        'monitoring_pasteurisasi_id',
        'batch',
        'production_batch_id',
    ];

    public function MonitoringPasteurisasi()
    {
        return $this->belongsTo(MonitoringPasteurisasi::class);
    }

    public function ProductionBatch()
    {
        return $this->belongsTo(ProductionBatch::class);
    }
}

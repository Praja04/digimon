<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonitoringTurunBlendingRelation extends Model
{
    //
    use HasFactory;
    protected $table = 'monitoring_turun_blending_relations';

    protected $fillable = ['monitoring_turun_blending_id', 'batch', 'production_batch_id'];

    public function monitoringTurunBlending()
    {
        return $this->belongsTo(MonitoringTurunBlending::class);
    }
}

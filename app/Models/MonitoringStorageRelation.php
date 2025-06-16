<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonitoringStorageRelation extends Model
{
    //
    protected $table = 'monitoring_storage_relations';

    protected $fillable = ['monitoring_storage_id', 'batch', 'production_batch_id'];

    public function monitoringStorage()
    {
        return $this->belongsTo(MonitoringStorageModel::class);
    }
}

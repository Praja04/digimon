<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonitoringPasteurisasiData extends Model
{
    protected $table = 'monitoring_pasteurisasi_data';

    protected $fillable = [
        'monitoring_pasteurisasi_id',
        'brix',
        'nacl',
        'bj',
        'visco',
        'aw',
        'buih',
        'ph',
        'organo',
        'endapan',
        'warna',
        'shift',
        'production_time',
        'created_by',
    ];

    public function MonitoringPasteurisasi()
    {
        return $this->belongsTo(MonitoringPasteurisasi::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonitoringTurunBlendingData extends Model
{
//
    //
    use HasFactory;
    protected $table = 'monitoring_turun_blending_data';
    protected $fillable = [
        'monitoring_turun_blending_id',     
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
        'shift',
        'created_by',
    ];

    public function MasterData()
    {
        return $this->belongsTo(MonitoringTurunBlending::class);
    }
}

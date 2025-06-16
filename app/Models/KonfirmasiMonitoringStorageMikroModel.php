<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KonfirmasiMonitoringStorageMikroModel extends Model
{
    //
    protected $table = 'konfirmasi_monitoring_storage_mikro';
    protected $fillable = [
        'monitoring_storage_mikro_id',
        'nama_analis',
        'shift',
    ];
}

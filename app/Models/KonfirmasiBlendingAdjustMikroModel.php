<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KonfirmasiBlendingAdjustMikroModel extends Model
{
    //
    use HasFactory;
    protected $table = 'konfirmasi_blending_after_adjust_mikro';
    protected $fillable = [
        'blending_after_adjust_mikro_id',
        'nama_analis',
        'shift',
    ];
}

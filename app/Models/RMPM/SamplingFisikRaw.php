<?php

namespace App\Models\RMPM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SamplingFisikRaw extends Model
{
    //
    use HasFactory;
    protected $table = 'sampling_fisik_raw';
    protected $fillable = [
        'id_identitas','leleh','warna_std','campuran','aroma_std','sesuai_std','created_by_user'
    ];
}

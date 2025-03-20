<?php

namespace App\Models\RMPM;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
class SamplingKondisiMobil extends Model
{
    // 
     use HasFactory;
    protected $table = 'sampling_kondisi_mobil';
    protected $fillable = [
        'id_identitas','bersih','kering','benda_asing','cacat','segel','berbau','created_by_user'
    ];
}

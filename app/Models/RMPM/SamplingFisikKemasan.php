<?php

namespace App\Models\RMPM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SamplingFisikKemasan extends Model
{
    //
    use HasFactory;
    protected $table = 'sampling_fisik_kemasan';
    protected $fillable = [
        'id_identitas','kotor','rusak','sesuai_std','lain-lain','berair','basah','campuran','created_by_user'
    ];
}

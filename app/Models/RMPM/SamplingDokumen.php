<?php

namespace App\Models\RMPM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SamplingDokumen extends Model
{
    //

    use HasFactory;
    protected $table = 'sampling_dokumen';
    protected $fillable = [
        'id_identitas','coa','suratjalan_vendor','packing_list','identitas_kemasan','logo_halal','kesesuaian_matriks_bahan','created_by_user'
        
    ];
}

<?php

namespace App\Models\RMPM;

use Illuminate\Database\Eloquent\Model;

class AnalisaGaramGula extends Model
{
    //
    protected $table = 'analisa_garam_gula';

    protected $fillable = [
        'id_identitas', 'fisik', '%ka', 'kotoran', 'organo', 'warna', 'aroma', '%nacl', 'gross_weight', 'disposisi'
    ];

    public function identitasRmMaster()
    {
        return $this->belongsTo(IdentitasRm::class, 'id_identitas_rm');
    }
}

<?php

namespace App\Models\RMPM;

use Illuminate\Database\Eloquent\Model;

class AnalisaGaramGula extends Model
{
    //
    protected $table = 'analisa_garam_gula';

    protected $fillable = [
        'id_identitas', 'fisik', '%ka', 'kotoran', 'organo', 'warna', 'aroma', '%nacl', 'gross_weight', 'id_disposisi','created_by_user'
    ];

    public function identitasRmMaster()
    {
        return $this->belongsTo(IdentitasRm::class, 'id_identitas');
    }
    public function disposisi()
    {
        return $this->belongsTo(DisposisiRm::class, 'id_disposisi');
    }
}

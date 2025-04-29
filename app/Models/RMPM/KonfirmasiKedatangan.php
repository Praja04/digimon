<?php

namespace App\Models\RMPM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KonfirmasiKedatangan extends Model
{
    //
    use HasFactory;
    protected $table = 'konfirmasi_rm';
    public $timestamps = false;
    protected $fillable = [
        'id_identitas',
        'jam_kedatangan',
        'jam_analisa',
        'diterima_by_user',
        'dianalisa_by_user',
    ];
}

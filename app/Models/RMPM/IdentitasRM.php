<?php

namespace App\Models\RMPM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdentitasRM extends Model
{
    //
    use HasFactory;
    protected $table = 'identitas_rm_master';
    protected $fillable = [
        'nama_bahan',
        'tanggal_kedatangan',
        'suplier_manufactur',
        'asal_bahan',
        'no_mobil',
        'no_spb',
        'jumlah_kedatangan',
        'lot_batch',
        'jenis_gula',

    ];

    public function samplingMobil()
    {
        return $this->hasOne(SamplingKondisiMobil::class, 'id_identitas');
    }

    public function samplingDokumen()
    {
        return $this->hasOne(SamplingDokumen::class, 'id_identitas');
    }

    public function samplingFisikKemasan()
    {
        return $this->hasOne(SamplingFisikKemasan::class, 'id_identitas');
    }

    public function samplingFisikRaw()
    {
        return $this->hasOne(SamplingFisikRaw::class, 'id_identitas');
    }

    public function analisaShortTerm()
    {
        return $this->hasMany(AnalisaShortTermGKT::class, 'id_identitas');
    }


    public function analisaLongTerm()
    {
        return $this->hasMany(AnalisaLongTermGKT::class, 'id_identitas');
    }

    public function analisaGaramGula()
    {
        return $this->hasMany(AnalisaGaramGula::class, 'id_identitas');
    }

    public function konfirmasi()
    {
        return $this->hasOne(KonfirmasiKedatangan::class, 'id_identitas');
    }

    public function isSamplingComplete()
    {
        if ($this->jenis_gula === 'Garam') {
            return $this->samplingMobil && $this->samplingDokumen && $this->samplingFisikKemasan && $this->samplingFisikRaw;
        } else {
            return $this->samplingMobil && $this->samplingDokumen && $this-> samplingFisikKemasan && $this->samplingFisikRaw;
        }
    }
}

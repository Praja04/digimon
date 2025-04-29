<?php

namespace App\Models\RMPM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisposisiRm extends Model
{
    use HasFactory;

    protected $table = 'disposisi_rm';

    protected $fillable = [
        'disposisi',
        'keterangan',
    ];

    public function analisaShortTerm()
    {
        return $this->hasMany(AnalisaShortTermGKT::class, 'id_disposisi');
    }

    public function analisaGaramGula()
    {
        return $this->hasMany(AnalisaGaramGula::class, 'id_disposisi');
    }
}

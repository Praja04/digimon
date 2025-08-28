<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManageWarnaModel extends Model
{
    //
    protected $table = 'manage_warna';
    protected $fillable = [
        'nama_warna',
        'code_warna'
    ];
}

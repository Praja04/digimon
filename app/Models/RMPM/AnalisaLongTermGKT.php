<?php

namespace App\Models\RMPM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalisaLongTermGKT extends Model
{
    //
    use HasFactory;
    protected $table = 'analisa_long_term_gkt';
    protected $fillable = [
        'id_identitas', 'uji_kristal','created_by_user'
    ];
}

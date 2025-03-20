<?php

namespace App\Models\RMPM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalisaShortTermGKT extends Model
{
    //
    use HasFactory;
    protected $table = 'analisa_short_term_gkt';
    protected $fillable = [
        'id_identitas','brix','ph','kotoran','ka','organo','warna','aroma','created_by_user'
    ];
}

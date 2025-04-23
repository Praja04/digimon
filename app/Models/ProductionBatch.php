<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number',
        'variant',
        'production_date',
        'batch_range',
        'storage',  // Langsung disimpan di sini
        'description',
    ];

    // Relasi dengan tabel GGA/GGAS Process
    public function ggaGgasProcesses()
    {
        return $this->hasMany(GgaGgasProcess::class);
    }
}

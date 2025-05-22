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
    public function getBatchRangeArrayAttribute()
    {
        if (preg_match('/(\d+)\s*-\s*(\d+)/', $this->batch_range, $matches)) {
            return range((int)$matches[1], (int)$matches[2]);
        }
        return [$this->batch_range]; // fallback kalau cuma satu angka
    }
    
    
    public function BlendingAwal()
    {
        return $this->hasMany(BlendingAwalModel::class);
    }
    public function GgaProcesses()
    {
        return $this->hasMany(GgaProcess::class);
    }
    public function GgasProcesses()
    {
        return $this->hasMany(GgasProcess::class);
    }
}

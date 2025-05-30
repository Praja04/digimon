<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlendingBatchRelation extends Model
{
    use HasFactory;

    protected $fillable = ['blending_awal_id', 'batch', 'production_batch_id'];

    public function blendingAwal()
    {
        return $this->belongsTo(BlendingAwalModel::class);
    }
}

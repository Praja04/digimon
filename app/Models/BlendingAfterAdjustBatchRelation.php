<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlendingAfterAdjustBatchRelation extends Model
{
    //
    use HasFactory;
    protected $table = 'blending_after_adjust_batch_relations';

    protected $fillable = ['blending_after_adjust_id', 'batch', 'production_batch_id'];

    public function blendingAdjust()
    {
        return $this->belongsTo(BlendingAfterAdjustModel::class);
    }
}

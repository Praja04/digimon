<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'production_batch_id',
        'title',
        'disposisi',
        'remark',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'unread',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productionBatch()
    {
        return $this->belongsTo(ProductionBatch::class);
    }
}

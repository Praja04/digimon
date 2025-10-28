<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProcessOutsideDisposition
{
    use Dispatchable, SerializesModels;

    public $title;
    public $disposisi;
    public $production_batch_id;
    public $remark;

    public function __construct($title, $production_batch_id, $disposisi, $remark)
    {
        $this->title = $title;
        $this->production_batch_id = $production_batch_id;
        $this->disposisi = $disposisi;
        $this->remark = $remark;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileUpload extends Model
{
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}

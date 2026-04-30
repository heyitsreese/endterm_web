<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileUpload extends Model
{
    protected $primaryKey = 'file_id';

    protected $fillable = [
        'order_id',
        'file_name',
        'file_path',
        'upload_date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}

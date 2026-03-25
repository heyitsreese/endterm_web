<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'order_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    public function files()
    {
        return $this->hasMany(FileUpload::class, 'order_id');
    }
}

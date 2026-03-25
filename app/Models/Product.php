<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'product_id');
    }
}

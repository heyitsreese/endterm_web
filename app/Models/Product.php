<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = 'product_id';

    protected $fillable = [
        'product_name',
        'base_price'
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'product_id');
    }
}
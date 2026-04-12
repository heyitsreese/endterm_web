<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\Product;

class OrderDetail extends Model
{
    protected $primaryKey = 'order_details_id';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'size',          
        'color',
        'paper_quality',
        'special_instruction',
        'file_path',
        'price',       
        'subtotal',     
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
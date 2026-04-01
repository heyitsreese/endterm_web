<?php

namespace App\Models;
use App\Models\OrderDetail;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey = 'order_id';
    
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'customer_name',
        'email',
        'phone_number',
        'status',
        'total_amount',
        'order_date',
        'order_token',
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }
}
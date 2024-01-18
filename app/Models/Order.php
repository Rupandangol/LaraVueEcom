<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;
    protected $table='orders';
    protected $fillable=[
        'user_id',
        'total_price',
        'shipping_address',
        'status',
    ];

    public function orderDetails() : HasMany {
        return $this->hasMany(OrderDetail::class,'order_id','id');
    }
}

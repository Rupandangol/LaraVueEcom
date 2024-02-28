<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;
    protected $table='orders';
    protected $fillable=[
        'user_id',
        'total_price',
        'country',
        'zone',
        'district',
        'street',
        'zip_code',
        'status',
    ];

    public function user() : BelongsTo {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function orderDetails() : HasMany {
        return $this->hasMany(OrderDetail::class,'order_id','id');
    }
}

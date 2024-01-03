<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table='products';
    protected $fillable=[
        'name',
        'description',
        'price',
        'stock_quantity',
        'category_id'
    ];

    public function getCategory(){
        return $this->hasOne(
            'App\Model\Category',
            'id',
            'category_id'
        );
    }
}

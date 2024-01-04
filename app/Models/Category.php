<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $tables='categories';
    protected $fillable=[
        'name',
        'description'
    ];

    public function getProducts()  {
        return $this->hasMany(
            'App\Model\Product',
            'category_id',
            'id'
        );
    }
}

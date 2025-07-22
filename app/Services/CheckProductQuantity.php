<?php

namespace App\Services;

use App\DTOs\CheckQuantityDto;
use App\Models\Product;
use Exception;

class CheckProductQuantity
{
    public static function check(CheckQuantityDto $checkQuantityDto)
    {
        try {
            $product = Product::find($checkQuantityDto->product_id);
            if ($product->stock_quantity >= $checkQuantityDto->quantity) {
                return true;
            }
        } catch (Exception $e) {
        }

        return false;
    }
}

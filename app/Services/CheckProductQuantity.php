<?php

namespace App\Services;

use App\Models\Product;
use Exception;

class CheckProductQuantity
{

    public static function check($product_id, $quantity)
    {
        try {
            $product = Product::find($product_id);
            if ($product->stock_quantity >= $quantity) {
                return true;
            }
        } catch (Exception $e) {
        }
        return false;
    }
}

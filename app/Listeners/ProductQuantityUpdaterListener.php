<?php

namespace App\Listeners;

use App\Enums\ProductQuantityType;
use App\Events\ProductQuantityUpdater;
use App\Models\Product;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProductQuantityUpdaterListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ProductQuantityUpdater $event): void
    {
        try {
            foreach ($event->productDetails as $item) {
                $product = Product::find($item['product_id']);
                $newQuantity = 0;
                if ($item['type'] == ProductQuantityType::TYPE_INCREMENT) {
                    $newQuantity = $product['stock_quantity'] + $item['quantity'];
                } elseif ($item['type'] == ProductQuantityType::TYPE_DECREMENT) {
                    $newQuantity = $product['stock_quantity'] - $item['quantity'];
                }
                $product->update([
                    'stock_quantity' => $newQuantity
                ]);
            }
        } catch (Exception $e) {
            dd('ERROR FROM CATCH==>', $e->getMessage());
        }
    }
}

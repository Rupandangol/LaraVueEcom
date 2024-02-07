<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->whenLoaded('user')),
            'total_price' => $this->total_price,
            'shipping_address' => $this->shipping_address,
            'status' => $this->status,
            'order_details' => new OrderDetailResource($this->whenLoaded('orderDetails')),
            'product'=>new ProductResource($this->whenLoaded('orderDetails.product'))
        ];
    }
}

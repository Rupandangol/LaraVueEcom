<?php

namespace App\Http\Resources\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read int $id
 * @property-read string $total_price
 * @property-read string|null $country   
 * @property-read string|null $zone
 * @property-read string|null $district  
 * @property-read string|null $street   
 * @property-read string|null $zip_code   
 * @property-read string|null $status   
 * @property-read string|null $created_at   
 */
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
            'country' => $this->country,
            'zone' => $this->zone,
            'district' => $this->district,
            'street' => $this->street,
            'zip_code' => $this->zip_code,
            'status' => $this->status,
            'order_details' => new OrderDetailResource($this->whenLoaded('orderDetails')),
            'product' => new ProductResource($this->whenLoaded('orderDetails.product')),
            'ordered_date' => Carbon::parse($this->created_at)->format('Y-m-d H:i'),
        ];
    }
}

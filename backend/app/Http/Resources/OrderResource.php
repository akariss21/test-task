<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'customer_name' => $this->customer_name,
            'order_date' => $this->order_date,
            'status' => $this->status,
            'comment' => $this->comment,
            'total_price' => $this->total_price, // accessor в модели
            'products' => $this->products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $product->pivot->quantity,
                    'subtotal' => $product->price * $product->pivot->quantity,
                ];
            }),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
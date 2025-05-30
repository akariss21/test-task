<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="OrderResource",
 *     type="object",
 *     title="OrderResource",
 *     properties={
 *         @OA\Property(property="id", type="integer", example=123),
 *         @OA\Property(property="customer_name", type="string", example="Ivan Ivanov"),
 *         @OA\Property(property="order_date", type="string", format="date", example="2025-05-30"),
 *         @OA\Property(property="status", type="string", example="new"),
 *         @OA\Property(property="comment", type="string", nullable=true, example="Please call before delivery"),
 *         @OA\Property(property="total_price", type="number", format="float", example=1500.50),
 *         @OA\Property(
 *             property="products",
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 properties={
 *                     @OA\Property(property="id", type="integer", example=10),
 *                     @OA\Property(property="name", type="string", example="Apples"),
 *                     @OA\Property(property="price", type="number", format="float", example=100.50),
 *                     @OA\Property(property="quantity", type="integer", example=3),
 *                     @OA\Property(property="subtotal", type="number", format="float", example=301.50)
 *                 }
 *             )
 *         ),
 *         @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-30T14:22:05Z")
 *     }
 * )
 */
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
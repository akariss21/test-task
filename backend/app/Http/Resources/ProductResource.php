<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
 *     schema="ProductResource",
 *     type="object",
 *     title="ProductResource",
 *     properties={
 *         @OA\Property(property="id", type="integer", example=10),
 *         @OA\Property(property="name", type="string", example="Apples"),
 *         @OA\Property(property="description", type="string", nullable=true, example="Fresh apples"),
 *         @OA\Property(property="price", type="number", format="float", example=100.50),
 *         @OA\Property(property="quantity", type="integer", example=50),
 *         @OA\Property(
 *             property="category",
 *             type="object",
 *             properties={
 *                 @OA\Property(property="id", type="integer", example=2),
 *                 @OA\Property(property="name", type="string", example="Fruits")
 *             }
 *         ),
 *         @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-30T14:22:05Z")
 *     }
 * )
 */
class ProductResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'price'       => $this->price,
            'quantity'    => $this->quantity,
            'category'    => [
                'id'   => $this->category?->id,
                'name' => $this->category?->name,
            ],
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}

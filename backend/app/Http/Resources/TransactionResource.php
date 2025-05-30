<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="TransactionResource",
 *     type="object",
 *     title="TransactionResource",
 *     properties={
 *         @OA\Property(property="id", type="integer", example=123),
 *         @OA\Property(property="type", type="string", example="deposit"),
 *         @OA\Property(property="amount", type="number", format="float", example=100.50),
 *         @OA\Property(property="order_id", type="integer", nullable=true, example=456),
 *         @OA\Property(property="seller_id", type="integer", nullable=true, example=789),
 *         @OA\Property(
 *             property="meta",
 *             type="object",
 *             description="Additional transaction data",
 *             example={"product_id": 12, "quantity": 2, "price": 50.25}
 *         ),
 *         @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-30T12:34:56Z")
 *     }
 * )
 */
class TransactionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'type'       => $this->type,
            'amount'     => $this->amount,
            'order_id'   => $this->order_id,
            'seller_id'  => $this->seller_id,
            'meta'       => json_decode($this->meta, true),
            'created_at' => $this->created_at,
        ];
    }
    public function history()
    {
        return TransactionResource::collection(auth()->user()->transactions()->latest()->get());
    }
}

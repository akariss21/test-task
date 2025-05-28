<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
